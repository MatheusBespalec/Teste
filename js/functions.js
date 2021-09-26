$(function(){

	$('.sinais').click(function(){
		var valor_atual =  parseInt($('#quantidade').val());
		var sinal = $(this).attr('sinal');
		if(sinal == 'mais')
			valor_atual++;
		else
			valor_atual--;
		$('#quantidade').val(valor_atual);
	})


	//Menu Lateral
	windowSize = $(window)[0].innerWidth;
	if(windowSize <= 768)
		var open = false;
	else
		var open = true;

	$('header .container > i,.menu > i').click(function(){

		if (open) {
			//Fechar Menu
			$('.menu').css('width','0');
			$('.wraper-menu').css('opacity','0');
			$('header').css('width','100%');
			$('.content').css('width','100%');

			open = false;
		}else{
			//Abrir Menu
			windowSize = $(window)[0].innerWidth;
			if(windowSize <= 550){
				//Tela Muito Pequena
				$('.menu').css('width','100%');
				$('.wraper-menu').css('width','100%');
				$('.wraper-menu').css('opacity','1');
				$('header').css('width','calc(100% - 300px)');
				$('.content').css('width','calc(100% - 300px)');
			}else{
				//Tela Normal
				$('.menu').css('width','300px');
				$('.wraper-menu').css('opacity','1');
				$('header').css('width','calc(100% - 300px)');
				$('.content').css('width','calc(100% - 300px)');
			}

			open = true;
		}

	})

	//Correção de Bug no resize
	$(window).resize(function(){
		windowSize = $(window)[0].innerWidth;
		if(windowSize <= 768){
			open = false;
			$('.menu').css('width','0');
			$('.wraper-menu').css('opacity','0');
			$('header').css('width','100%');
			$('.content').css('width','100%');
		}else{
			open = true;
			$('.menu').css('width','300px');
			$('.wraper-menu').css('opacity','1');
			$('header').css('width','calc(100% - 300px)');
			$('.content').css('width','calc(100% - 300px)');
		}
	})

	//Confirm
	$('a.delete').click(function(){
		var nome = $(this).attr('tipo');
		var r = confirm('Este '+nome+' será excluido, clique em "ok" para confirmar!');
		if (r == false) 
			return false
	})

	//Mask
	$('#preco,#custo').mask("#.##0,00", {reverse: true});
	$('#telefone').mask('(00) 00000-0000');

})