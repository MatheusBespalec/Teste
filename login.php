<!DOCTYPE html>
<html>
	<head>
		<!-- Links -->
			<!-- CSS -->
			<link rel="stylesheet" type="text/css" href="<?php echo INCLUDE_PATH; ?>css/style.css">
			<!-- Google Fonts -->
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
		<!--  -->

		<title>Login</title>

		<!-- Meta Tags -->
			<!-- UTF-8 -->
			<meta charset="utf-8">
			<!-- Responsivo -->
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- Autor -->
			<meta name="author" content="Matheus Bespalec - matheusbespalec@gmail.com">
		<!--  -->

		<!-- Icone -->
		<link rel="icon" href="" type="image/x-icon" />
	</head>
	<body style="display: flex; justify-content: center; align-items: center;padding: 0 2%;">

		<form class="login" method="post">
			<?php

				if (isset($_POST['action'])) {
					//Verificação com PHP Campos Vazios
					$null = false;
					foreach ($_POST as $key => $value) {
						if ($value == '')
							$null = true;
					}

					if ($null == false){
						//Verificação se Usuario Existe
						if(User::userExists($_POST['user'],$_POST['pass'])){
							User::Login($_POST['user'],$_POST['pass']);
							Painel::redirect(INCLUDE_PATH);
						}else{
							Painel::alert('error','Usuario ou senha incorretos!');
						}
					}else{
						Painel::alert('error','Campos vazios não são permitidos!');
					}
				}

			?>
			<h2>Faça seu Login!</h2>
			<div class="form-group">
				<label>Usuário:</label>
				<input type="text" name="user" required>
			</div><!--form-group-->

			<div class="form-group">
				<label>Senha:</label>
				<input type="password" name="pass" required>
			</div><!--form-group-->

			<input type="submit" name="action" value="Entrar!">
		</form>

		<!-- Scripts -->
		<script src="js/jquery.js"></script>
		<script src="js/functions.js"></script>
		<script src="https://kit.fontawesome.com/4053268ba0.js" crossorigin="anonymous"></script>
		<!--  -->
	</body>
</html>
