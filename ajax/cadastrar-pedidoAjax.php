<?php

	include('../config.php');

	//Real Time Cliente ID
	if(isset($_POST['busca'])){
		$cliente = $_POST['cliente'];
		if($cliente == ''){
			$data['resultado'] = false;
		}else{
			$buscaCliente = Painel::selectAll('tb_admin.clientes','WHERE id LIKE \'%'.$cliente.'%\' OR nome LIKE \'%'.$cliente.'%\'',[]);

			if(count($buscaCliente) > 0){
				$data['cliente'] = [];
				foreach($buscaCliente as $key => $value){
					$data['cliente'][] = ['nome'=>$value['nome'],'id'=>$value['id'],'endereco'=>$value['endereco']];
				}

				$data['resultado'] = true;
			}else{
				$data['resultado'] = false;
			}
		}

		die(json_encode($data));
	}

	//Lista de Produtos
	if(isset($_POST['btn'])){
		$itemId    = $_POST['itemId'];
		$itemNome  = Painel::getElement('tb_admin.itens','id = ?',[$itemId],'nome');
		$precoUnid = Painel::getElement('tb_admin.itens','id = ?',[$itemId],'preco'); 

		//Return data
		$data['itemNome']  = $itemNome;
		$data['precoUnid'] = $precoUnid;
		
		die(json_encode($data));
	}

	//finalizar pedido
	if(isset($_POST['final'])){
		$retira = $_POST['retira'];

		//Constants
		$pedido     = Painel::getElement('tb_admin.auxiliar','nome = ?',['ultimo_pedido'],'valor') + 1;
		$date       = date("Y/m/d"); 
		$pago       = 1;
		$clienteId  = $_POST['clienteId'];

		//Produtos
		$itemId     = $_POST['itemId'];
		$quant      = $_POST['quant'];
		$precoVenda = $_POST['precoVenda'];
		
		foreach ($itemId as $key => $value) {
			//Colunas do Pedido
			$item_id     = $itemId[$key];
			$quantidade  = $quant[$key];
			$preco_venda = str_replace(',', '.', $precoVenda[$key]);
			$custo       = Painel::getElement('tb_admin.itens','id = ?',[$item_id],'custo');
			$lucroBruto  = $quantidade * $preco_venda;
			$lucroLiq    = ($preco_venda - $custo)* $quantidade;
			$status      = 1;

			//INSERT Pedido Final
			if ($retira == 'true') {
				$saida    = 'Retira';
				$endereco = '';

				Painel::insert('tb_admin.pedidos',[null,$pedido,$date,$clienteId,$item_id,$quantidade,$preco_venda,$custo,$lucroBruto,$lucroLiq,$status,$pago,$saida,$endereco]);
			}else{
				$saida    = 'Entrega';
				$endereco = $_POST['endereco'];

				Painel::insert('tb_admin.pedidos',[null,$pedido,$date,$clienteId,$item_id,$quantidade,$preco_venda,$custo,$lucroBruto,$lucroLiq,$status,$pago,$saida,$endereco]);
			}
			
		}
		Painel::update('tb_admin.auxiliar',['valor'=>$pedido],'nome = ?',['ultimo_pedido']);
		die();
	}

?>