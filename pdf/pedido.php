<?php 
	
	//Verificar se Esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Virificar se existe um pedido
	if(isset($_GET['pedido']))
		$pedidoId = $_GET['pedido'];
	else
		die(Painel::alert('error','Nenhum pedido selecionado'));
	
	//Verifica se o Pedido Existe
	if(Painel::exists('tb_admin.pedidos','id',$pedidoId) == false)
		die(Painel::alert('error','Este pedido não existe'));
	
?>

<div class="box-content">

	<h2> Numero do Pedido: <?php echo $pedidoId; ?></h2>
	
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
		?>
		<table style="min-width: 800px;">
			<tr style="background-color: #000;">
				<td style="font-weight: bold;color: #fff;">ID</td>
				<td style="font-weight: bold;color: #fff;">Produto</td>
				<td style="font-weight: bold;color: #fff;">Quantidade</td>
				<td style="font-weight: bold;color: #fff;">Preço de Venda(unid)</td>
				<td style="font-weight: bold;color: #fff;">Total</td>
			</tr>
			<?php foreach ($pedido as $key => $value) { 
				
			?>
				<tr>
					<td><?php echo $value['item_id']; ?></td>
					<td id='id_item'><?php echo Painel::getElement('tb_admin.itens','id = ?',[$value['item_id']],'nome'); ?></td>
					<td><?php echo $value['quantidade']; ?></td>
					<td><?php echo 'R$ '.number_format( $value['preço_venda(uni)'], 2, ',', ' ' ); ?></td>
					<td><?php echo 'R$ '.number_format( $value['lucro_bruto'], 2, ',', ' ' ); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="background-color: #ccc;">Total:</td>
				<td style="background-color: #ccc;">R$ <?php echo number_format( $totalBruto, 2, ',', ' ' ); ?></td>
		</table>
	</div><!--wraper-table-->
	<p><b>Nome do Cliente:</b>     <?php echo $cliente; ?></p>
	<p><b>Endereço de Entrega:</b> <?php echo $saida == 'Retira' ? 'Cliente Retira' : $endereco; ?>
	<p><b>Data de Emissão:</b>     <?php echo $data; ?></p>
	<br>
	<p>
		<b>Status:</b> <?php echo Painel::getElement('tb_admin.status_pedido','id = ?',[$status],'status'); ?>
	</p>
	<p style="margin: 10px 0;">
		<b>Pago:</b> <?php echo Painel::getElement('tb_admin.pago','id = ?',[$pago],'pago'); ; ?>
	</p>
	
</div><!--box-content-->