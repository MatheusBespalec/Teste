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
			case 'nome':
				$nome = $_POST['nome'];

				$list = Painel::selectAll('tb_admin.clientes','WHERE nome LIKE \'%'.$nome.'%\'',[]);
			break;

			case 'id':
				$id = $_POST['id'];

				$list = Painel::selectAll('tb_admin.clientes','WHERE id = ?',[$id]);
			break;
		}
	}
	
?>

<div class="box-content">

	<h2><i class="fas fa-search"></i> Bucar Cliente:</h2>

	<div class="search">
		<p>Procurar Por:</p>
		<span id="nome" class="select">Nome</span>
		<span id="id">ID</span>
		<div class="clear"></div><!--clear-->
	</div><!--search-->

	<form method="post"  class="search nome">

		<div class="form-group">
			<label>Digite o nome do cliente:</label>
			<input type="text" name="nome" >
			<input type="hidden" name="indentificador" value="nome">
		</div><!--form-group-->

		<input type="submit" name="action" value="Buscar!">

	</form>

	<form method="post" style="display:none;"  class="search id">

		<div class="form-group">
			<label>Digite o ID do cliente:</label>
			<input type="text" name="idCliente" id="cliente_id">
			<input type="hidden" name="indentificador" value="id">
		</div><!--form-group-->

		<input type="submit" name="action" value="Buscar!">

	</form>
	
</div><!--box-content-->

<?php if (isset($_POST['action'])){ ?>

<div class="box-content">
	<h2><i class="fas fa-search"></i> Resultado:</h2>
	<div class="wraper-table">
		<table>
			<tr>
				<td>ID</td>
				<td>Nome</td>
				<td>Telefone</td>
				<td>Endereço</td>
				<td><i class="fas fa-pencil-alt"></i></td>
				<td><i class="fas fa-times"></i></td>
			</tr>
			<?php
				foreach ($list as $key => $value) {
			?>
					<tr>
						<td><?php echo $value['id']; ?></td>
						<td><?php echo $value['nome']; ?></td>
						<td><?php echo $value['telefone']; ?></td>
						<td><?php echo $value['endereco']; ?></td>
						<td><a class="btn edit" href="editar-cliente?edit=<?php echo $value['id'];?>">Informações</a></td>
						<td><a tipo="cliente" class="btn delete" href="?delete=<?php echo $value['id'];?>">Excluir</a></td>
					</tr>
			<?php 
				} 
			?>
		</table>
	</div><!--wraper-table-->
</div><!--box-content-->

<?php } ?>