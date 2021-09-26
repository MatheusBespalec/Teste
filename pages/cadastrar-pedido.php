<?php 

	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	$cardapio = Painel::selectAll('tb_admin.cardapio','WHERE atual = ?',['Sim']);
?>
<div class="box-content">

	<h2><i class="far fa-plus-square"></i> Cadastrar Pedido</h2>
	
	<form method="post" class="pedido">
		<h3>Cardápio Atual: <?php echo $cardapio[0]['nome']; ?></h3>
		<cardapio id="<?php echo $cardapio[0]['id']; ?>"/>
		<br>
		
		<label>Cliente:</label>
		<div class="form-group">
			<input type="text" id="cliente" name="cliente" placeholder="ID ou Nome do Cliente..." required>
			<div class="buscando">
				
			</div><!--buscando-->
		</div><!--form-group-->
		<label>Produto:</label><!--Produto Quantidade |Inserir|-->
		<div class="form-group">
			<select id="item_id" name="item">
				<option value="Selecione um item" >Selecione um item</option>,
				<?php foreach ($cardapio as $key => $value) { ?>
					<option 
						value="<?php echo $value['item_id']; ?>" 
						nome="<?php echo Painel::getElement('tb_admin.itens','id = ?',[$value['item_id']],'nome'); ?>">
						<?php echo $value['item_id'].' - '.Painel::getElement('tb_admin.itens','id = ?',[$value['item_id']],'nome'); ?>
					</option>
				<?php } ?>
			</select>
		</div><!--form-group-->

		<label>Quantidade:</label>
		<div class="form-group">
			<div class="quant">
				<div style="border-top-left-radius: 5px;border-bottom-left-radius: 5px;" class="sinais" sinal="menos">-</div><!--sinais-->
				<input type="text" value="0" id="quantidade" name="quantidade" required>
				<div style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;" class="sinais" sinal="mais">+</div><!--sinais-->
			</div><!--quant-->
			<button type="button">Adicionar Item!</button>
		</div><!--form-group-->

		<div class="wraper-alert"></div><!--wraper-alert-->
		
		<div class="wraper-table">
			<table>
				<tr id="first">
					<td><i class="fas fa-times"></i></td>
					<td>ID</td>
					<td>Nome</td>
					<td>Quantidade</td>
					<td>Preço(uni)</td>
					<td>Total</td>
				</tr>
				<tr style="border-bottom: none;">
					<td style="background-color: #ed823b;"></td>
					<td style="background-color: #ed823b;"></td>
					<td style="background-color: #ed823b;color: #fff;">Cliente:</td>
					<td style="background-color: #ed823b;color: #fff;" id="clienteTable"></td>
					<td style="background-color: #ccc;">Total:</td>
					<td id="total" style="background-color: #ccc;"></td>
				</tr>
			</table>
		</div><!--wraper-table-->

		<label>Cliente Retira:</label>
		<div class="form-group">
			<div class="option">
				<div class="disc"></div><!--disc-->
				<input type="hidden" id="retira" name="retira" value="true">
			</div><!--option-->
		</div><!--form-group-->

		<div class="endereco">
			<label>Endereço de entrega:</label>
			<div class="form-group">
				<input type="text" id="endereco" name="endereco" value="">
			</div><!--form-group-->
		</div><!--endereco-->
			
		<input style="padding: 20px 0;" type="submit" name="action" value="Finalizar Pedido!">
	</form>
	
</div><!--box-content-->