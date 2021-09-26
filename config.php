<?php
	
	session_start();
	date_default_timezone_set('America/Sao_Paulo');

	//AutoLoad Classes
	$autoload = function($class){
		$class = str_replace('\\','/',$class);
		include('class/'.$class.'.php');
	};

	spl_autoload_register($autoload);

	//Diretorios
	define('INCLUDE_PATH', 'http://localhost/sistema-pequeno-negocio/');
	define('BASE_DIR', __DIR__);

	//Conexão Banco de Dados
	define('HOST','localhost');
	define('DB','padaria');
	define('USER','root');
	define('PASS', '');
?>