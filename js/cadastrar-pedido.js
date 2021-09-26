$(function(){
	//Mask
	$('#cliente_id,#quantidade').mask('00000');

	//Animação Liga/Desliga
	var enable = true;
	$('.option').click(function(){
		if (enable) {
			//Inserir Endereço de Entrega
			$(this).css('background-color','#ccc');
			$('.option .disc').css('right','47px');
			$('div.endereco').css('height','100px');
			$('#retira').val('false');
			enable = false;
		}else{
			//Cliente Retira
			$(this).css('background-color','#65db84');
			$('.option .disc').css('right','2px');
			$('div.endereco').css('height','0');
			$('#retira').val('true');
			enable = true;
		}
	})

	//Cliente em tempo real
	$('#cliente').keyup(function(){
		var cliente = $(this).val();
		var busca = true;
		$('#cliente').attr('valid',false);
		$('.buscando').show();
		$('#clienteTable').html('');

		$.ajax({
			url: include_path+'ajax/cadastrar-pedidoAjax.php',
			type: 'POST', 
			dataType: 'json', 
			data: {busca,cliente}
		}).done(function(data){
			if(data['resultado']){
				$('.buscando').html('');
				for (var i = 0; i < data['cliente'].length; i++) {
					$('.buscando').prepend('\
						<div class="item-busca">\
							<span class="cliente_id">'+data['cliente'][i]['id']+'</span>\
							<span> - </span>\
							<span class="cliente_nome">'+data['cliente'][i]['nome']+'</span>\
							<span class="endereco" endereco="'+data['cliente'][i]['endereco']+'"></span>\
						</div><!--item-busca-->\
					');
				}
			}else{
				$('.buscando').html('');
				$('.buscando').prepend('\
					<div style="width:100%;padding:10px;text-align:center;">\
						<span"><b>Nenhum cliente não encontrado!</b></span>\
					</div>\
				');
				$('#cliente').css('border-color','red');
			}
		});
	});

	$('body').on('click','.item-busca',function(){
		var id = $(this).find('span.cliente_id').html();
		var nome = $(this).find('span.cliente_nome').html();
		var endereco = $(this).find('span.endereco').attr('endereco');

		$('input[name=endereco]').val(endereco);
		$('#cliente').attr('cliente_id',id);
		$('#cliente').attr('cliente_nome',nome);
		$('#cliente').attr('valid',true);
		$('#cliente').css('border-color','green');
		$('#clienteTable').html(id+' - '+nome);

		$('input[name=cliente]').val(id+' - '+nome);
		$('.buscando').hide();
	})

	$('#cliente').focusout(function(){
		setTimeout(function(){
			$('.buscando').hide();	
		},200);
		
	})

	//Alerta 
	function boxAlert(result,txt){
		$('.wraper-alert').html('');
		$('.wraper-alert').prepend('<div class="alert '+result+'">'+txt+'</div><!--alert-->');

		setTimeout(function(){
			$('.wraper-alert div').animate({opacity:0},700);
		},3000)
	}

	//Variavel do total do pedido
	var somaPedido = 0;

	//Lista de Itens do Pedido
	$('button').click(function(){
		var itemId = $('#item_id').val();
		var quant = $('#quantidade').val();
		var btn = true;
		var repet = false;
		var itens = $('#item_id').length;

		if (itemId != '' && itemId != 'Selecione um item') {
			//Vereficar se é repetido
			for (var i = 0; i < itens; i++) {
			if($('#itemId').eq(i).html() == itemId)
				repet = true;
			}
			
			if (repet == false) {
				if (quant > 0 && quant != '') {
					$.ajax({
						url: include_path+'ajax/cadastrar-pedidoAjax.php',
						type: 'POST', 
						dataType: 'json', 
						data: {itemId,btn},
					}).done(function(data){
						//Preço unitario do produto
						var unid = parseFloat(data['precoUnid']).toFixed(2);
						//Preço do produto * quantidade do item
						var totalItem = parseFloat(quant*data['precoUnid']);
						//Total do Pedido
						somaPedido = somaPedido + totalItem;
						
						//Adicionar item na tabela
						$('#first').after('\
							<tr class="produto">\
								<td><a href="" class="btn delete">Excluir</a></td>\
								<td id="itemId">'+itemId+'</td>\
								<td>'+data['itemNome']+'</td>\
								<td id="quant">'+quant+'</td>\
								<td>R$ <span id="precoVenda">'+unid.toString().replace(".", ",")+'</span></td>\
								<td>R$ '+totalItem.toFixed(2).toString().replace(".", ",")+'</td>\
							</tr>');

						$('#item_id').val('Selecione um item');
						$('#quantidade').val('0');

						//Alterar quantidade total
						$('#total').html('R$ '+parseFloat(somaPedido).toFixed(2).toString().replace(".", ","));

						//Callback de sucesso
						boxAlert('success',quant+' '+data['itemNome']+' adicionados ao pré-pedido!');
					});
				}else{
					boxAlert('error','Quantidade deve ser maior que 0!');
				}
			}else{
				boxAlert('error','Este item ja foi inserido!');
			}
		}else{
			boxAlert('error','Você precisa colocar um Item Valido!');
		}
	});

	//Ecluir Pré-Pedido
	$('body').on('click','a.delete',function(){
		$(this).parent().parent().remove();
		return false;
	})

	// Finalizar Pedido
	$('input[type=submit]').click(function(){
		//Parametros
		var final      = true;
		var retira     = $('#retira').val();
		var endereco   = $('#endereco').val();
		var cliente    = $('#cliente').val();

		cliente   = cliente.split('-');
		clienteId = cliente[0];
		//Verificar se Cliente é Válido
		if($('#cliente').attr('valid') == 'true'){
			//Verificar se Existem Itens no pedido
			var itemId      = [];
			var quant       = [];
			var precoVenda  = [];
			var numProdutos = $('tr.produto').length;
			if(numProdutos > 0){
				for (var i = 0; i < numProdutos; i++) {
					itemId[i]     = $('tr.produto #itemId').eq(i).html();
					quant[i]      = $('tr.produto #quant').eq(i).html();
					precoVenda[i] = $('tr.produto #precoVenda').eq(i).html();
				}
				//Pedido se o cliente for Retirar
				if (retira == 'true') {
					$.ajax({
						url: include_path+'ajax/cadastrar-pedidoAjax.php',
						type: 'POST', 
						dataType: 'json', 
						data: {final,clienteId,retira,itemId,quant,precoVenda},
					}).always(function(){
						$('.overlay').css('display','block');
					});
				//Pedido se tivermos que enviar o Produto
				}else if(retira == 'false' && endereco != ''){
					$.ajax({
						url: include_path+'ajax/cadastrar-pedidoAjax.php',
						type: 'POST', 
						dataType: 'json', 
						data: {final,clienteId,retira,endereco,itemId,quant,precoVenda},
					}).always(function(){
						$('.overlay').css('display','block');
					});
				//Pedido se tivermos que enviar mas o endereço estiver vazio!
				}else{
					boxAlert('error','O Cliente não possui endereço cadastrado, insira um endereço de entrega!');
				}
			}else{
				boxAlert('error','Você precisa Inserir os itens primeiro!');
			}
		}else{
			boxAlert('error','Insira um cliente Válido!');
		}
		return false;
	})

})