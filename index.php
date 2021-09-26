<?php 
	include('config.php');

	if(explode('/', @$_GET['url'])[0] == 'gerar-pdf'){
		include('gerar-pdf.php');
		die();
	}

	if(Painel::login())
		include('painel.php');
	else
		include('login.php');

?>