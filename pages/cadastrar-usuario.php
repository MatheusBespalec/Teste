<?php 

	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
?>
<div class="box-content">

	<h2><i class="far fa-plus-square"></i> Cadastrar Usuario</h2>
	
	<form method="post" enctype="multipart/form-data">
		<?php

			if(isset($_POST['action'])){
				//Sem Imagem
				if($_FILES['image']['name'] == ''){
					if(Painel::insert('tb_admin.user',[null,$_POST['user'],$_POST['pass'],''])){
						Painel::alert('success','Novo usuário cadastrado com sucesso!');
					}else{
						Painel::alert('error','Erro ao Cadastrar usuario!');
					}
				}else{
					//Com Imagem
					if (User::validImage($_FILES['image'])) {
						$img = User::uploadImage($_FILES['image'],$_POST['user']);
						if(Painel::insert('tb_admin.user',[null,$_POST['user'],$_POST['pass'],$img])){
							Painel::alert('success','Usuário cadastrado com sucesso!');
						}else{
							Painel::alert('error','Erro ao cadastrar usuario!');
						}
					}
					
				}
			}

		?>
		<div class="form-group">
			<label>Usuário:</label>
			<input type="text" name="user">
		</div><!--form-group-->
		
		<div class="form-group">
			<label>Senha:</label>
			<input type="text" name="pass">
		</div><!--form-group-->

		<div class="form-group">
			<label>Foto:</label>
			<input type="file" name="image">
		</div><!--form-group-->

		<input type="submit" name="action" value="Cadastrar!">
	</form>
	
</div><!--box-content-->