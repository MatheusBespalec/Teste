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

})