<?php 
	
	//Verificar se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
	//Verificar se tem Cliente
	if(isset($_GET['cliente']))
		$id = $_GET['cliente'];
	else
		die(Painel::alert('error','Cliente não selecionado!'));

	//Verificar se o cliente existe
	if(Painel::exists('tb_admin.clientes','id',$id) == false)
		die(Painel::alert('error','Cliente não existe!'));

	$cliente = Painel::select('tb_admin.clientes','WHERE id = ?',[$id]);

?>
<div class="box-content">

	<h2><i class="fas fa-calendar-week"></i> Sobre <?php echo $cliente['nome'] ?></h2>

	<p><b>Nome:</b> <?php echo $cliente['nome']; ?></p>
	<p><b>Telefone:</b> <?php echo $cliente['telefone']; ?></p>
	<p><b>Endereço:</b> <?php echo $cliente['endereco']; ?></p>

	<div class="wraper-painel">
			<h4>Comprou (nos ultimos 30 dias):</h4>
			<?php 

				$data1 = date('Y-m-d');
				$data2 = date('Y-m-d', strtotime('-30 days'));
				$query = 'cliente_id = '.$id.' AND pago = 3 AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\'';
				$lucroMes = Painel::total('tb_admin.pedidos','lucro_bruto',$query);

			?>
			<p>R$ <?php echo number_format( $lucroMes, 2, ',', ' ' ); ?></p>

			<h4>Numero de Pedidos (nos ultimos 30 dias):</h4>
			<?php 

				$query = 'WHERE cliente_id = '.$id.' AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\' GROUP BY id';
				$quantPedido = count(Painel::selectAll('tb_admin.pedidos',$query,[]));

			?>
			<p><?php echo $quantPedido; ?></p>

			<h4>Média por Pedido:</h4>
			<p>R$ <?php echo ($lucroMes != 0) ? number_format($lucroMes/$quantPedido, 2, ',', ' ' ) : 0; ?></p>

			<h4>Total de Compras:</h4>
			<p>R$ <?php echo number_format($cliente['vendas'], 2, ',', ' ' ); ?></p>

		
	</div><!--wraper-painel-->

	<h2><i class="fas fa-chart-line"></i> Pedidos de <?php echo $cliente['nome']; ?></h2>

	<div class="wraper-table">
		<table style="min-width: 800px;">
			<tr style="background-color: #000;">
				<td style="font-weight: bolder;font-size: 18px;color: #fff;">Pedido</td>
				<td style="font-weight: bolder;font-size: 18px;color: #fff;">Data</td>
				<td style="font-weight: bolder;font-size: 18px;color: #fff;">Total</td>
				<td style="font-weight: bolder;font-size: 18px;color: #fff;">Status</td>
				<td style="font-weight: bolder;font-size: 18px;color: #fff;">Pago</td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.pedidos','WHERE cliente_id = ? GROUP BY id ORDER BY data DESC ',[$id]);
				foreach ($list as $key => $value) { 
			?>
				<tr>
					<td><?php echo $value['id']; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
					<td><?php echo 'R$ '.number_format(Painel::total('tb_admin.pedidos','lucro_bruto','id = '.$value['id']), 2, ',', ' ' ); ?></td>
					<td><?php echo Painel::getElement('tb_admin.status_pedido','id =?',[$value['status']],'status'); ?></td>
					<td><?php echo Painel::getElement('tb_admin.pago','id =?',[$value['pago']],'pago'); ?></td>
				</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->
	
</div><!--box-content-->