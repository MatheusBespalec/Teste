<?php 
	
	//Verificar se Esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Ações Link
	if (isset($_GET['delete'])) {
		$deleteId = intval($_GET['delete']);

		Painel::delete('tb_admin.pedidos',$deleteId);
		header('Location: '.INCLUDE_PATH.'lista-de-pedidos');
	}else if (isset($_GET['status'])) {
		$statusId = intval($_GET['status']);
		$pedidoId = intval($_GET['id']);

		Painel::update('tb_admin.pedidos',['status'=>$statusId],'WHERE id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH.'buscar-pedido');
	}

	if(isset($_POST['action'])){
		switch ($_POST['indentificador']) {
			case 'numero':
				$numPedido = $_POST['numPedido'];

				$list = Painel::selectAll('tb_admin.pedidos','WHERE id = ? GROUP BY id',[$numPedido]);
			break;

			case 'data':
				$data1 = $_POST['data1'];
				$data2 = $_POST['data2'];

				$list = Painel::selectAll('tb_admin.pedidos','WHERE data BETWEEN \''.$data1.'\' AND \''.$data2.'\' GROUP BY id ORDER BY data DESC',[]);
			break;

			case 'cliente':
				$numCliente = $_POST['numCliente'];

				$list = Painel::selectAll('tb_admin.pedidos','WHERE cliente_id = ? GROUP BY id',[$numCliente]);
			break;
		}
	}
	
?>

<div class="box-content">

	<h2><i class="fas fa-search"></i> Bucar Pedido:</h2>

	<div class="search">
		<p>Procurar Por:</p>
		<span id="numero" class="select">Numero</span>
		<span id="data">Data</span>
		<span id="cliente">Cliente</span>
		<div class="clear"></div><!--clear-->
	</div><!--search-->

	<form method="post"  class="search numero">

		<div class="form-group">
			<label>Digite o numero do pedido:</label>
			<input type="text" name="numPedido">
			<input type="hidden" name="indentificador" value="numero">
		</div><!--form-group-->

		<input type="submit" name="action" value="Buscar!">

	</form>

	<form method="post" style="display:none;"  class="search data">

		<div class="form-group">
			<label>De:</label>
			<input type="date" name="data1">
			<label>Até:</label>
			<input type="date" name="data2">
			<input type="hidden" name="indentificador" value="data">
		</div><!--form-group-->
		<input type="submit" name="action" value="Buscar!">

	</form>

	<form method="post" class="search cliente pedido" style="display:none;">

		<label>Digite o numero do cliente:</label>
		<div class="form-group">
			<input type="text" id="cliente_id" name="numCliente" class="w50">
			<input type="text" id="cliente_nome" value="Nome..." disabled class="w50">
			<input type="hidden" name="indentificador" value="cliente">
		</div><!--form-group-->
		<input type="submit" name="action" value="Buscar!"  style="margin-left: 0;">

	</form>
	
</div><!--box-content-->

<?php if (isset($_POST['action'])){ ?>

<div class="box-content">
	<h2><i class="fas fa-search"></i> Resultado:</h2>
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
				foreach ($list as $key => $value) { 
			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
						<td><?php echo Painel::getElement('tb_admin.clientes','id = ?',[$value['cliente_id']],'nome'); ?></td>
						<td><?php echo 'R$ '.number_format(Painel::total('tb_admin.pedidos','lucro_bruto','id = '.$value['id']), 2, ',', ' ' ); ?></td>
						<td><?php echo Painel::getElement('tb_admin.status_pedido','id = ?',[$value['status']],'status'); ?></td>
						<td><?php echo Painel::getElement('tb_admin.pago','id = ?',[$value['pago']],'pago'); ?></td>
						<td><a class="btn view" href="pedido?view=<?php echo $value['id']; ?>">Ver Pedido</a></td>
					</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->
</div><!--box-content-->

<?php } ?>