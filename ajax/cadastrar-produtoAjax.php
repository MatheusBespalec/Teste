<?php
	include('../config.php');

	if ($_POST['nome'] != '' && $_POST['preco'] != '' && $_POST['custo'] != '') {
		if (Painel::exists('tb_admin.itens','nome',$_POST['nome'])){
			$data['type'] = 'error';
			$data['txt']  = 'Já existe um produto com o mesmo nome!';

			die(json_encode($data));
		}else{
			$preco = str_replace(',','.',$_POST['preco']);
			$custo = str_replace(',','.',$_POST['custo']);

			Painel::insert('tb_admin.itens',[null,$_POST['nome'],$preco,$custo,'0']);

			$data['type'] = 'success';
			$data['txt']  = 'Produto '.$_POST['nome'].' cadastrado com sucesso!';

			die(json_encode($data));
		}
	}else{
		$data['type'] = 'error';
		$data['txt']  = 'Campos vazios não são permitidos';
		
		die(json_encode($data));
	}	
		
?>