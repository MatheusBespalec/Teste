<?php 
	
	//Verificar se Esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Ações de Links
	if (isset($_GET['delete'])) {
		$deleteId = intval($_GET['delete']);

		Painel::delete('tb_admin.pedidos',$deleteId);
		header('Location: '.INCLUDE_PATH.'lista-de-pedidos');
	}

	if (isset($_GET['status']) && $_GET['status'] == 2) {
		$statusId = $_GET['status'];
		$pedidoId = $_GET['id'];

		Painel::update('tb_admin.pedidos',['status'=>$statusId],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH.'lista-de-pedidos');
	}

	if (isset($_GET['pago']) && $_GET['pago'] == 2) {
		$pago = $_GET['pago'];
		$pedidoId = $_GET['id'];

		Painel::update('tb_admin.pedidos',['pago'=>$pago],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH.'lista-de-pedidos');
	}

	if (isset($_GET['pago']) && isset($_GET['status']) && $_GET['pago'] == 3 && $_GET['status'] == 3) {
		$pago         = $_GET['pago'];
		$lucroCliente = $_GET['lucro'];
		$produtos     = explode('-',$_GET['produtos']);
		$precoProduto = explode('-',$_GET['precoprodutos']);
		$statusId     = $_GET['status'];
		$pedidoId     = $_GET['id'];

		$cliente       = Painel::select('tb_admin.pedidos','WHERE id = ?',[$pedidoId])['cliente_id'];
		$vendasCliente = Painel::select('tb_admin.clientes','WHERE id = ?',[$cliente])['vendas'] + $lucroCliente;

		array_pop($produtos);
		array_pop($precoProduto);

		for ($i = 0; $i < count($produtos); $i++) { 
			$vendasProduto = Painel::select('tb_admin.itens','WHERE id=?',[$produtos[$i]])['vendas']+$precoProduto[$i];
			Painel::update('tb_admin.itens',['vendas'=>$vendasProduto],' id = ?',[$produtos[$i]]);
		}

		Painel::update('tb_admin.clientes',['vendas'=>$vendasCliente],' id = ?',[$cliente]);
		Painel::update('tb_admin.pedidos',['status'=>$statusId],'id = ?',[$pedidoId]);
		Painel::update('tb_admin.pedidos',['pago'=>$pago],'id = ?',[$pedidoId]);

		header('Location: '.INCLUDE_PATH.'lista-de-pedidos');
	}

	//Paginação
	if (isset($_GET['pagina'])) {
		$pag = (int)$_GET['pagina'];
	}else{
		$pag = 1;
	}

	//Parametros Paginação
	$numEl = 10;
	$start = ($pag * $numEl) -$numEl;
	$arrCount = ceil(count(Painel::selectAll('tb_admin.pedidos','WHERE status = ? OR pago = ? GROUP BY id',[1,1])) / $numEl);
	
?>

<div class="box-content">

	<h2><i class="fas fa-spinner"></i> Pedidos com Pendência</h2>
	
	<div class="wraper-table">
		<table style="min-width: 1200px;">
			<tr>
				<td>Pedido</td>
				<td>Data</td>
				<td>Cliente</td>
				<td>Total</td>
				<td>Status</td>
				<td></td>
				<td>Pago</td>
				<td></td>
				<td>Saída</td>
				<td><i class="far fa-eye"></i></td>
				<td><i class="fas fa-times"></i></td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.pedidos','WHERE status = ? OR pago = ? GROUP BY id LIMIT '.$start.','.$numEl,[1,1]);
				foreach ($list as $key => $value) { 
			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
						<td><?php echo Painel::getElement('tb_admin.clientes','id =?',[$value['cliente_id']],'nome'); ?></td>
						<td><?php echo 'R$ '.number_format(Painel::total('tb_admin.pedidos','lucro_bruto','id = '.$value['id']), 2, ',', ' ' ); ?></td>
						<td><?php echo Painel::getElement('tb_admin.status_pedido','id =?',[$value['status']],'status'); ?></td>
						<td>
							<?php if ($value['status'] == 1) { ?>
								<a class="btn edit" href="?status=2&id=<?php echo $value['id']; ?>">Foi Entregue</a>
							<?php }else{ ?>
								<a class="btn edit" href="?status=1&id=<?php echo $value['id']; ?>">Desfazer</a>
							<?php } ?>
						</td>
						<td><?php echo Painel::getElement('tb_admin.pago','id =?',[$value['pago']],'pago'); ?></td>
						<td>
							<?php if ($value['pago'] == 1) { ?>
								<a class="btn edit" href="?pago=2&id=<?php echo $value['id'];?>">Foi Pago</a>
							<?php }else{ ?>
								<a class="btn edit" href="?pago=1&id=<?php echo $value['id']; ?>">Desfazer</a>
							<?php } ?>
						</td>
						<td><?php echo $value['saida']; ?></td>
						<td><a class="btn view" href="pedido?view=<?php echo $value['id']; ?>">Ver Pedido</a></td>
						<td><a class="btn delete" tipo="pedido" href="?delete=<?php echo $value['id'];?>">Excluir</a></td>
					</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->

	<?php if($arrCount > 1){?>
		<div class="pagination">
			<?php 
				for ($i = 1 ;$i <= $arrCount ;$i++) {  ?>
					<span><a class="<?php if($pag == $i) {echo select;} ?>" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></span>
			<?php } ?>
		</div><!--pagination-->
	<?php } ?>

</div><!--box-content-->

<div class="box-content">

	<h2><i class="fas fa-check"></i> Pedidos Aguardando Confirmação</h2>
	
	<div class="wraper-table">
		<table style="min-width: 1150px;">
			<tr>
				<td>Pedido</td>
				<td>Data</td>
				<td>Cliente</td>
				<td>Total</td>
				<td>Status</td>
				<td></td>
				<td>Pago</td>
				<td></td>
				<td><i class="far fa-eye"></i></td>
				<td>#</td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.pedidos','WHERE status = ? AND pago = ? GROUP BY id',[2,2]);
				foreach ($list as $key => $value) { 
				$lucroBruto = Painel::total('tb_admin.pedidos','lucro_bruto','id = '.$value['id']);
				$produtos = Painel::selectAll('tb_admin.pedidos','WHERE id = ?',[$value['id']]); 

			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
						<td><?php echo Painel::getElement('tb_admin.clientes','id =?',[$value['cliente_id']],'nome'); ?></td>
						<td><?php echo 'R$ '.number_format($lucroBruto, 2, ',', ' ' ); ?></td>
						<td><?php echo Painel::getElement('tb_admin.status_pedido','id =?',[$value['status']],'status'); ?></td>
						<td><a class="btn edit" href="?status=1&id=<?php echo $value['id']; ?>">Desfazer</a></td>
						<td><?php echo Painel::getElement('tb_admin.pago','id =?',[$value['pago']],'pago'); ?></td>
						<td><a class="btn edit" href="?pago=1&id=<?php echo $value['id']; ?>">Desfazer</a></td>
						<td><a class="btn view" href="pedido?view=<?php echo $value['id']; ?>">Ver Pedido</a></td>
						<td><a class="btn confirm" href="?status=3
							&pago=3
							&lucro=<?php echo $lucroBruto; ?>
							&id=<?php echo $value['id']; ?>
							&produtos=<?php foreach($produtos as $key => $value){
								echo $value['item_id'].'-';
							} ?>
							&precoprodutos=<?php foreach($produtos as $key => $value){
								echo $value['lucro_bruto'].'-';
							} ?>">Confirmar Ações</a></td>
					</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->

</div><!--box-content-->

<div class="box-content">

	<h2><i class="fas fa-check-double"></i> Ultimos Pedidos Finalizados</h2>
	
	<div class="wraper-table">
		<table style="min-width: 800px;">
			<tr>
				<td>Pedido</td>
				<td>Data</td>
				<td>Cliente</td>
				<td>Total</td>
				<td>Status</td>
				<td>Pago</td>
				<td><i class="far fa-eye"></i></td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.pedidos','WHERE status = ? AND pago = ? GROUP BY id ORDER BY data DESC LIMIT 10',[3,3]);
				foreach ($list as $key => $value) { 
			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
						<td><?php echo Painel::getElement('tb_admin.clientes','id =?',[$value['cliente_id']],'nome'); ?></td>
						<td><?php echo 'R$ '.number_format(Painel::total('tb_admin.pedidos','lucro_bruto','id = '.$value['id']), 2, ',', ' ' ); ?></td>
						<td><?php echo Painel::getElement('tb_admin.status_pedido','id =?',[$value['status']],'status'); ?></td>
						<td><?php echo Painel::getElement('tb_admin.pago','id =?',[$value['pago']],'pago'); ?></td>
						<td><a class="btn view" href="pedido?view=<?php echo $value['id']; ?>">Ver Pedido</a></td>
					</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->

</div><!--box-content-->