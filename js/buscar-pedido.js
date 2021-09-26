$(function(){
	// Alternar Form
	$('.search span').click(function(){
		//Manipular Span
		$('.search span').removeClass('select');
		$(this).addClass('select');

		//manipular Form
		let id = $(this).attr('id');
		$('form').css('display','none');
		$('.'+id).css('display','block');
	})

	//Cliente em tempo real
	$('#cliente_id,#item_id').mask('0000');

	$('#cliente_id').keyup(function(){
		var cliente_id = $(this).val();
		$.ajax({
			url: include_path+'ajax/buscar-pedidoAjax.php',
			type: 'POST', 
			dataType: 'json', 
			data: {cliente_id}
		}).done(function(clientNome){
			$('#cliente_nome').val(clientNome);
		});
	});

})