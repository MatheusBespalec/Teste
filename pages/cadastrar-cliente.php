<?php 

	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
?>
<div class="box-content">

	<h2><i class="far fa-plus-square"></i> Cadastrar Cliente</h2>
	
	<form method="post" id="myForm">
		<div class="wraper-alert"></div><!--wraper-alert-->
		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required>
		</div><!--form-group-->
		
		<div class="form-group">
			<label>Telefone:</label>
			<input type="text" id="telefone" name="telefone">
		</div><!--form-group-->

		<div class="form-group">
			<label>Endereço:</label>
			<input type="text" name="endereco">
		</div><!--form-group-->
		<input type="submit" name="action" value="Cadastrar!">
	</form>
	
</div><!--box-content-->