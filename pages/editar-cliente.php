<?php 
	
	//Verificar se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
	//Verificar se tem Cliente
	if(isset($_GET['edit']))
		$id = $_GET['edit'];
	else
		die(Painel::alert('error','Cliente não selecionado!'));

	//Verificar se o cliente existe
	if(Painel::exists('tb_admin.clientes','id',$id) == false)
		die(Painel::alert('error','Cliente não existe!'));

	$cliente = Painel::select('tb_admin.clientes','WHERE id = ?',[$id]);

	//Paginação
	if (isset($_GET['pagina'])) {
		$pag = (int)$_GET['pagina'];
	}else{
		$pag = 1;
	}

	//Parametros Paginação
	$numEl = 10;
	$start = ($pag * $numEl) -$numEl;
	$arrCount = ceil(count(Painel::selectAll('tb_admin.pedidos','WHERE cliente_id = ? GROUP BY id',[$id])) / $numEl);

?>
<div class="box-content">

	<?php

		if (isset($_POST['action'])) {
			$nome     = $_POST['nome'];
			$endereco = $_POST['endereco'];
			$telefone = $_POST['telefone'];

			Painel::update('tb_admin.clientes',['nome'=>$nome,'telefone'=>$telefone,'endereco'=>$endereco],' id = ?',[$id]);
			header('Location: '.INCLUDE_PATH.'lista-de-clientes');
		}

	?>
	<a class="btn pdf" target="_blank" href="<?php echo INCLUDE_PATH; ?>gerar-pdf/cliente?cliente=<?php echo $id; ?>">Gerar PDF do Cliente</a>
	<h2><i class="fas fa-user-edit"></i> Editar Cliente</h2>
	
	<form method="post" id="myForm">
		<div class="wraper-alert"></div><!--wraper-alert-->
		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required value="<?php echo $cliente['nome']; ?>">
		</div><!--form-group-->
		
		<div class="form-group">
			<label>Telefone:</label>
			<input type="text" id="telefone" name="telefone" value="<?php echo $cliente['telefone']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<label>Endereço:</label>
			<input type="text" name="endereco" value="<?php echo $cliente['endereco']; ?>">
		</div><!--form-group-->
		<input type="submit" name="action" value="Atalizar!">

	</form>
	
</div><!--box-content-->

<div class="box-content">

	<h2><i class="fas fa-calendar-week"></i> Sobre <?php echo $cliente['nome'] ?></h2>

	<div class="wraper-painel">
		<div class="box-painel">
			<h4>Comprou (ultimos 30 dias):</h4>
			<?php 

				$data1 = date('Y-m-d');
				$data2 = date('Y-m-d', strtotime('-30 days'));
				$query = 'cliente_id = '.$id.' AND pago = 3 AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\'';
				$lucroMes = Painel::total('tb_admin.pedidos','lucro_bruto',$query);

			?>
			<p>R$ <?php echo number_format( $lucroMes, 2, ',', ' ' ); ?></p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4>Numero de Pedidos (30 dias):</h4>
			<?php 

				$query = 'WHERE cliente_id = '.$id.' AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\' GROUP BY id';
				$quantPedido = count(Painel::selectAll('tb_admin.pedidos',$query,[]));

			?>
			<p><?php echo $quantPedido; ?></p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4>Média por Pedido:</h4>
			<p>R$ <?php echo ($lucroMes != 0) ? number_format($lucroMes/$quantPedido, 2, ',', ' ' ) : 0; ?></p>
		</div><!--box-painel-->

		
	</div><!--wraper-painel-->

</div><!--box-content-->

<div class="box-content">

	<h2><i class="fas fa-chart-line"></i> Graficos</h2>

	<?php 

		$total = [];
		$ano = date('Y');
		for ($i = 0; $i < 12; $i++) { 
			$mes = $i + 1;

			$pedidos = Painel::selectAll('tb_admin.pedidos','WHERE YEAR(data) = \''.$ano.'\' AND 
      		MONTH(data) = \''.$mes.'\' AND cliente_id = ?',[$id]);
			if(count($pedidos) > 0){
				foreach($pedidos as $key => $value){
					@$total[$i]+= $value['lucro_bruto'];
				}
			}else{
				@$total[$i]+= 0;
			}

		}	

	?>

	<h3>Volume de Compras de <?php echo $cliente['nome']; ?> (Em <?php echo $ano ?>)</h3>
	<vendasano
		<?php foreach($total as $key => $value){ 
			 echo 'mes'.$key.'="'.$value.'" '; 
		 } ?>
	/>
	<div class="wraper-chart">
		<div class="chart">
			<canvas id="vendasAno"></canvas>
		</div>
	</div><!--wraper-chart-->
	
</div><!--box-content-->

<div class="box-content">

	<h2><i class="fas fa-chart-line"></i> Pedidos de <?php echo $cliente['nome']; ?></h2>

	<div class="wraper-table">
		<table style="min-width: 800px;">
			<tr>
				<td>Pedido</td>
				<td>Data</td>
				<td>Total</td>
				<td>Status</td>
				<td>Pago</td>
				<td><i class="far fa-eye"></i></td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.pedidos','WHERE cliente_id = ? GROUP BY id ORDER BY data DESC LIMIT '.$start.','.$numEl,[$id]);
				foreach ($list as $key => $value) { 
			?>
				<tr>
					<td><?php echo $value['id']; ?></td>
					<td><?php echo implode('/',array_reverse(explode('-', $value['data']))); ?></td>
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

	<?php if($arrCount > 1){?>
		<div class="pagination">
			<?php 
				for ($i = 1 ;$i <= $arrCount ;$i++) {  ?>
					<span><a class="<?php if($pag == $i) {echo select;} ?>" href="?edit=<?php echo $id; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></span>
			<?php } ?>
		</div><!--pagination-->
	<?php } ?>
	
</div><!--box-content-->