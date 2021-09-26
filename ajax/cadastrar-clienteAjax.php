<?php
	include('../config.php');

	if ($_POST['nome'] != '') {
			if (Painel::exists('tb_admin.clientes','nome',$_POST['nome'])){
				$data['type'] = 'error';
				$data['txt']  = 'Já existe um cliente com o nome '.$_POST['nome'].'!';
				
				die(json_encode($data));
			}else{
				Painel::insert('tb_admin.clientes',[null,$_POST['nome'],$_POST['telefone'],$_POST['endereco']]);

				$data['type'] = 'success';
				$data['txt']  = 'Cliente '.$_POST['nome'].' cadastrado com sucesso';

				die(json_encode($data));
			}
	}else{
		$data['type'] = 'error';
		$data['txt']  = 'Campo nome não pode estar vazio!';

		die(json_encode($data));
	}	
		
?>