<?php 
	
	//Verifica se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
	//Verifica se existe produto
	if(isset($_GET['edit']))
		$id = $_GET['edit'];
	else
		die(Painel::alert('error','Nenhum produto selecionado!'));

	//Verifica se o produto existe
	if(Painel::exists('tb_admin.itens','id',$id) == false)
		die(Painel::alert('error','Produto não existe!'));


	$item = Painel::select('tb_admin.itens','WHERE id = ?',[$id]);

?>
<div class="box-content">

	<?php

	if (isset($_POST['action'])) {
		$nome  = $_POST['nome'];
		$preco = $_POST['preco'];
		$custo = $_POST['custo'];

		$preco = str_replace(',','.',$_POST['preco']);
		$custo = str_replace(',','.',$_POST['custo']);

		Painel::update('tb_admin.itens',['nome'=>$nome,'preco'=>$preco,'custo'=>$custo],'WHERE id = ?',[$id]);
		header('Location: '.INCLUDE_PATH.'lista-de-produtos');
	}

	?>

	<h2><i class="fas fa-user-edit"></i> Editar Produto</h2>
	
	<form method="post" id="myForm">
		<div class="wraper-alert"></div><!--wraper-alert-->
		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required value="<?php echo $item['nome'] ?>">
		</div><!--form-group-->
		
		<div class="form-group">
			<label>Preço:</label>
			<input type="text" id="preco" name="preco" required value="<?php echo number_format( $item['preco'], 2, ',', ' ' ); ?>">
		</div><!--form-group-->

		<div class="form-group">
			<label>Custo:</label>
			<input type="text" id="custo" name="custo" required value="<?php echo number_format( $item['custo'], 2, ',', ' ' ); ?>">
		</div><!--form-group-->
		<input type="submit" name="action" value="Atalizar!">

	</form>
	
</div><!--box-content-->

<div class="box-content">

	<h2><i class="far fa-money-bill-alt"></i> Sobre o Produto</h2>

	<div class="wraper-painel">
		<div class="box-painel">
			<h4>Lucro (ultimos 30 dias):</h4>
			<?php 

				$data1 = date('Y-m-d');
				$data2 = date('Y-m-d', strtotime('-30 days'));
				$query = 'item_id = '.$id.' AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\'';
				$lucroMes = Painel::total('tb_admin.pedidos','lucro_bruto',$query);

			?>
			<p>R$ <?php echo $lucro = number_format($lucroMes, 2, ',', ' ' ); ?></p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4>% de Pedidos que Contém <?php echo $item['nome'] ?> (30 dias):</h4>
			<?php 

				$query = 'WHERE item_id = '.$id.' AND data BETWEEN \''.$data2.'\' AND \''.$data1.'\'';
				$quantPedido = count(Painel::selectAll('tb_admin.pedidos',$query,[]));
				$pedidos = count(Painel::selectAll('tb_admin.pedidos','GROUP BY id',[]));
				$porcPedidos = (100*$quantPedido)/$pedidos;

			?>
			<p><?php echo number_format($porcPedidos, 2, ',', ''); ?>%</p>
		</div><!--box-painel-->

		<div class="box-painel">
			<h4><?php echo $item['nome'] ?> Vendidos:</h4>
			<p><?php echo Painel::total('tb_admin.pedidos','quantidade','item_id = '.$id); ?></p>
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
      		MONTH(data) = \''.$mes.'\' AND item_id = ?',[$id]);
			if(count($pedidos) > 0){
				foreach($pedidos as $key => $value){
					@$total[$i]+= $value['lucro_bruto'];
				}
			}else{
				@$total[$i]+= 0;
			}

		}	

	?>

	<h3>Vendas de <?php echo $item['nome'] ?> (Em <?php echo $ano ?>)</h3>
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