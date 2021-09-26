<?php 
	
	//Verificar se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Ações Link
	if (isset($_GET['delete'])) {
		$deleteId = intval($_GET['delete']);

		Painel::delete('tb_admin.itens',$deleteId);
		header('Location: '.INCLUDE_PATH.'lista-de-produtos');
	}

	//Paginação
	if (isset($_GET['pagina'])) {
		$pag = (int)$_GET['pagina'];
	}else{
		$pag = 1;
	}

	//Parametros Paginação
	$numEl = 15;
	$start = ($pag * $numEl) -$numEl;
	$arrCount = ceil(count(Painel::selectAll('tb_admin.itens','',[])) / $numEl);
	
?>
<div class="box-content">

	<h2><i class="fas fa-list"></i> Lista de Produtos</h2>

	<div class="wraper-table">
		<table style="min-width: 700px">
		
			<tr>
				<td>Código</td>
				<td>Nome</td>
				<td>Preço</td>
				<td>Custo</td>
				<td><i class="fas fa-pencil-alt"></i></td>
				<td><i class="fas fa-times"></i></td>
			</tr>
			<?php
				$list = Painel::selectAll('tb_admin.itens','LIMIT '.$start.','.$numEl,[]);
				foreach ($list as $key => $value) { 
			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo $value['nome']; ?></td>
						<td><?php echo 'R$ '.number_format( $value['preco'], 2, ',', ' ' ); ?></td>
						<td><?php echo 'R$ '.number_format( $value['custo'], 2, ',', ' ' ); ?></td>
						<td><a class="btn edit" href="editar-produto?edit=<?php echo $value['id'];?>">Informações</a></td>
						<td><a tipo="produto" class="btn delete" href="?delete=<?php echo $value['id'];?>">Excluir</a></td>
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
					<span><a class="<?php if($pag == $i) {echo 'select';} ?>" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></span>
			<?php } ?>
		</div><!--pagination-->
	<?php } ?>
	
</div><!--box-content-->