$(function(){
	$('body').on('submit','form',function(){
    	$.ajax({
	        url: include_path+'ajax/cadastrar-produtoAjax.php',
	        type: 'POST', 
	        dataType: "json", 
	        data: $('form').serialize(),
      }).done(function(data){
      		$('.wraper-alert').html('');
	    	$('.wraper-alert').prepend('<div class="alert '+data['type']+'">'+data['txt']+'</div><!--alert-->');
	    	document.getElementById("myForm").reset();
	    	setTimeout(function(){
	    		$('.wraper-alert div').animate({opacity:0},700);
	    	},4000)
      });
      return false;
  	});
})