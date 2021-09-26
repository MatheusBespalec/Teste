<?php

	include('../config.php');

	//Cliente ID em tempo real
	if(isset($_POST['cliente_id'])){
		$clienteId = $_POST['cliente_id'];

		if(Painel::exists('tb_admin.clientes','id',$clienteId)){
			$clientNome = Painel::getElement('tb_admin.clientes','id = ?',[$clienteId],'nome');
			die(json_encode($clientNome));
		}else{
			$clientNome = 'Não Existe!';
			die(json_encode($clientNome));
		}
	}

?>