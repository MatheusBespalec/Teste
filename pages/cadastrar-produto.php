<?php 

	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
?>
<div class="box-content">

	<h2><i class="far fa-plus-square"></i> Cadastrar Produto</h2>
	
	<form method="post" id="myForm">
		<div class="wraper-alert"></div><!--wraper-alert-->
		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required>
		</div><!--form-group-->
		
		<div class="form-group">
			<label>Preço:</label>
			<input type="text" id="preco" name="preco" required>
		</div><!--form-group-->

		<div class="form-group">
			<label>Custo:</label>
			<input type="text" id="custo" name="custo" required>
		</div><!--form-group-->
		<input type="submit" name="action" value="Cadastrar!">

	</form>
	
</div><!--box-content-->