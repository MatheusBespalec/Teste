<?php 
	
	//Verificar se Esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Virificar se existe um pedido
	if(isset($_GET['view']))
		$pedidoId = $_GET['view'];
	else
		die(Painel::alert('error','Nenhum pedido selecionado'));
	
	//Verifica se o Pedido Existe
	if(Painel::exists('tb_admin.pedidos','id',$pedidoId) == false)
		die(Painel::alert('error','Este pedido não existe'));
	
	//Ação Links
	if (isset($_GET['status'])) {
		$statusId = intval($_GET['status']);

		Painel::update('tb_admin.pedidos',['status'=>$statusId],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH.'pedido?view='.$pedidoId);
	}else if (isset($_GET['pago'])) {
		$pago = $_GET['pago'];

		Painel::update('tb_admin.pedidos',['pago'=>$pago],'id = ?',[$pedidoId]);
		header('Location: '.INCLUDE_PATH.'pedido?view='.$pedidoId);
	}

	

?>
<div class="box-content">

	<h2><i class="far fa-list-alt"></i> Pedido Numero: <?php echo $pedidoId; ?></h2>
	<a class="btn pdf" target="_blank" href="<?php echo INCLUDE_PATH; ?>gerar-pdf/pedido?pedido=<?php echo $pedidoId; ?>">Gerar PDF</a>
	<div style="margin: 10px;"></div>
	<div class="wraper-table">
		<?php
			//Select Pedido
			$pedido = Painel::selectAll('tb_admin.pedidos','WHERE id = ?',[$pedidoId]);
			
			//Parametros
			$data         = implode('/',array_reverse(explode('-',$pedido[0]['data'])));
			$cliente      = Painel::getElement('tb_admin.clientes','id = ?',[$pedido[0]['cliente_id']],'nome');
			$pago         = $pedido[0]['pago'];
			$saida        = $pedido[0]['saida'];
			$endereco     = $pedido[0]['endereco'];
			$status       = $pedido[0]['status'];
			$totalBruto   = Painel::totalArr($pedido,'lucro_bruto');
			$totalLiquido = Painel::totalArr($pedido,'lucro_liquido');
		?>
		<table style="min-width: 800px;">
			<tr>
				<td>Item ID</td>
				<td>Item</td>
				<td>Quantidade</td>
				<td>Preço de Venda(unid)</td>
				<td>Custo(unid)</td>
				<td>Lucro Liquido</td>
				<td>Lucro Bruto</td>
			</tr>
			<?php foreach ($pedido as $key => $value) { 
				
			?>
				<tr>
					<td><?php echo $value['item_id']; ?></td>
					<td id='id_item'><?php echo Painel::getElement('tb_admin.itens','id = ?',[$value['item_id']],'nome'); ?></td>
					<td><?php echo $value['quantidade']; ?></td>
					<td><?php echo 'R$ '.number_format( $value['preço_venda(uni)'], 2, ',', ' ' ); ?></td>
					<td><?php echo 'R$ '.number_format( $value['custo(uni)'], 2, ',', ' ' ); ?></td>
					<td><?php echo 'R$ '.number_format( $value['lucro_bruto'], 2, ',', ' ' ); ?></td>
					<td><?php echo 'R$ '.number_format( $value['lucro_liquido'], 2, ',', ' ' ); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="background-color: #ccc;">Total:</td>
				<td style="background-color: #ccc;">R$ <?php echo number_format( $totalLiquido, 2, ',', ' ' ); ?></td>
				<td style="background-color: #ccc;">R$ <?php echo number_format( $totalBruto, 2, ',', ' ' ); ?></td>
		</table>
	</div><!--wraper-table-->
	<p><b>Nome do Cliente:</b>     <?php echo $cliente; ?></p>
	<p><b>Endereço de Entrega:</b> <?php echo $saida == 'Retira' ? 'Cliente Retira' : $endereco; ?>
	<p><b>Data de Emissão:</b>     <?php echo $data; ?></p>
	<br>
	<p>
		<b>Status:</b> 
		<?php echo Painel::getElement('tb_admin.status_pedido','id = ?',[$status],'status'); ?>
		<?php if ($status == 1) { ?>
			<a class="btn edit" href="?view=<?php echo $pedidoId ?>&status=2&id=<?php echo $pedido[0]['id']; ?>">Marcar como Entregue</a>
		<?php }else if($status == 2){ ?>
			<a class="btn edit" href="?view=<?php echo $pedidoId ?>&status=1&id=<?php echo $pedido[0]['id']; ?>">Desfazer</a>
		<?php } ?>
	</p>
	<p style="margin: 10px 0;">
		<b>Pago:</b>   <?php echo Painel::getElement('tb_admin.pago','id = ?',[$pago],'pago'); ; ?>
		<?php if ($pago == 1) { ?>
			<a class="btn edit" href="?view=<?php echo $pedidoId ?>&pago=2&id=<?php echo $pedido[0]['id'];?>">Marcar como Pago</a>
		<?php }else if($pago == 2){ ?>
			<a class="btn edit" href="?view=<?php echo $pedidoId ?>&pago=1&id=<?php echo $pedido[0]['id']; ?>">Desfazer</a>
		<?php } ?>
	</p>

	<?php if ($pago == 2 && $status ==2) { ?>
		<a class="btn confirm" href="?status=3&id=<?php echo $value['id']; ?>">Confirmar Ações</a>
	<?php } ?>
	
</div><!--box-content-->