<?php 

	//Verificar se Esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Ações dos links
	if (isset($_GET['delete'])) {
		$deleteId = intval($_GET['delete']);
		
		Painel::delete('tb_admin.pedidos',$deleteId);
		header('Location: '.INCLUDE_PATH);
	}else if (isset($_GET['status'])) {
		$statusId = intval($_GET['status']);
		$pedidoId = intval($_GET['id']);

		Painel::update('tb_admin.pedidos',['status'=>$statusId],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH);
	}else if (isset($_GET['pago'])) {
		$pago = $_GET['pago'];
		$pedidoId = intval($_GET['id']);

		Painel::update('tb_admin.pedidos',['pago'=>$pago],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH);
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
	$arrCount = ceil(count(Painel::selectAll('tb_admin.pedidos','WHERE status = ? GROUP BY id',[1])) / $numEl);

	//Filtro Data
	$data1 = date('Y-m-d');
	$data2 = date('Y-m-d', strtotime('-30 days'));
	$query = 'data BETWEEN \''.$data2.'\' AND \''.$data1.'\'';
	$lucroMes = Painel::total('tb_admin.pedidos','lucro_bruto',$query);
	
?>
<div class="box-content">

	<h2><i class="fas fa-sliders-h"></i> Painel de Controle</h2>

	<div class="wraper-painel">
		<div class="box-painel">
			<h4>Lucro Bruto (30 dias):</h4>
			<p>R$ <?php echo $lucro = number_format( $lucroMes, 2, ',', ' ' ); ?></p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4>Numero de pedidos (30 dias):</h4>
			<p><?php echo $qPedidos = count(Painel::selectAll('tb_admin.pedidos','WHERE data BETWEEN \''.$data2.'\' AND \''.$data1.'\' GROUP BY id',[])); ?></p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4>Média de lucro por Pedido:</h4>
			<p>R$ <?php echo ($qPedidos == 0) ? 0 : number_format($lucroMes/$qPedidos, 2, ',', ' ' ); ?></p>
		</div><!--box-painel-->
	</div><!--wraper-painel-->

</div><!--box-content-->

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