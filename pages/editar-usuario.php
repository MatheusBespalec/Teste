<?php 
	
	//Verificar se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');
	
?>
<div class="box-content">

	<h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>
	
	<form method="post" enctype="multipart/form-data">
		<?php

			if(isset($_POST['action'])){
				//Sem Imagem
				if($_FILES['image']['name'] == ''){
					$user = $_SESSION['user'];

					if(Painel::update('tb_admin.user',['pass'=>$_POST['pass']],"WHERE user = ?",[$user])){
						$_SESSION['pass'] = $_POST['pass'];
					}else{
						alert('error','Erro ao atualizar usuario!');
					}
				}else{
					//Com Imagem
					if (User::validImage($_FILES['image'])) {
						if($img = $_FILES['image']){
							User::deleteImg($_SESSION['image']);

							$img  = User::uploadImage($img,$_SESSION['user']);
							$user = $_SESSION['user'];

							if(Painel::update('tb_admin.user',['pass'=>$_POST['pass'],'img'=>$img],"WHERE user=?",[$user])){
								$_SESSION['pass']  = $_POST['pass'];
								$_SESSION['image'] = $img;
								
								Painel::alert('success','Usuário atualizado com sucesso!');
							}else{
								Painel::alert('error','Erro ao atualizar usuario!');
							}
						}
					}
				}
			}

		?>
		<div class="form-group">
			<label>Foto:</label>
			<input type="file" name="image">
		</div><!--form-group-->
		<div class="form-group">
			<label>Senha:</label>
			<input type="text" name="pass" value="<?php echo $_SESSION['pass'];?>">
		</div><!--form-group-->
		<input type="submit" name="action" value="Atalizar!">
	</form>
	
</div><!--box-content-->