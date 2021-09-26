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

		<title>Painel de Controle</title>

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
	<body>
		<!-- Conteudo Box Alert -->

			<div class="overlay">
				<div class="box-alert">
					<div class="alert success">Seu Pedido foi cadastrado com sucesso!</div>
					<br>
					<a href="cadastrar-pedido">Ok!</a>
				</div><!--box-alert-->
			</div><!--overlay-->

			<div class="overlay-cardapio">
				<div class="box-alert">
					<div class="alert success">Seu Cardapio foi cadastrado com sucesso!</div>
					<br>
					<a href="cadastrar-cardapio">Ok!</a>
				</div><!--box-alert-->
			</div><!--overlay-->

		<!-- Fim Conteudo Box Alert -->

		<header class="right">
			<div class="container">

				<i class="fas fa-bars"></i>

				<a class="right" href="<?php echo INCLUDE_PATH.'?loggout' ?>"><i class="fas fa-sign-out-alt"> SAIR</i></a>
				<?php

					if(isset($_GET['loggout'])){
						Painel::loggout();
						header('Location: '.INCLUDE_PATH);
					}

				?>
				<div class="clear"></div><!--clear-->

			</div><!--container-->
		</header>
		<div class="clear"></div><!--clear-->

		<section class="content right">
			
			<?php
	
				Painel::loadPage();

			?>

		</section><!--content-->
		<div class="clear"></div><!--clear-->

		<div class="menu">
			<i class="fas fa-times"></i>
			<div class="wraper-menu">
				<section class="user">
					<div class="avatar-img">
						<?php if ($_SESSION['image'] == '') {?>
							<i class="fas fa-user"></i>
						<?php }else{?>
							<img src="<?php echo INCLUDE_PATH.'images/users/'.$_SESSION['image']; ?>">
						<?php } ?>
						
					</div><!--avatar-img-->
					<h2><?php echo ucfirst($_SESSION['user']); ?></h2>
				</section><!--user-->
				<section class="list">
					<?php 
						if (isset($_GET['url']))
							$url = $_GET['url'];
						else
							$url = 'painel-de-controle';

					?>
					<h3>Home</h3>
					<a class="<?php if($url == 'painel-de-controle') echo 'select';?>" href="painel-de-controle"> Painel de Controle</a>
					<a class="<?php if($url == 'editar-usuario') echo 'select';?>" href="editar-usuario"> Editar Usuário</a>
					<a class="<?php if($url == 'desempenho') echo 'select';?>" href="desempenho"> Graficos de Desempenho</a>

					<h3>Busca</h3>
					<a class="<?php if($url == 'buscar-pedido') echo 'select';?>" href="buscar-pedido"> Buscar pedido</a>
					<a class="<?php if($url == 'buscar-cliente') echo 'select';?>" href="buscar-cliente"> Buscar cliente</a>

					<h3>Listas</h3>
					<a class="<?php if($url == 'lista-de-pedidos') echo 'select';?>" href="lista-de-pedidos"> Lista de Pedidos</a>
					<a class="<?php if($url == 'lista-de-produtos') echo 'select';?>" href="lista-de-produtos"> Lista de Produtos</a>
					<a class="<?php if($url == 'lista-de-clientes') echo 'select';?>" href="lista-de-clientes"> Lista de Clientes</a>

					<h3>Cadastro</h3>
					<a class="<?php if($url == 'cadastrar-pedido') echo 'select';?>" href="cadastrar-pedido"> Cadastrar Pedido</a>
					<a class="<?php if($url == 'cadastrar-cliente') echo 'select';?>" href="cadastrar-cliente"> Cadastrar Cliente</a>
					<a class="<?php if($url == 'cadastrar-produto') echo 'select';?>" href="cadastrar-produto"> Cadastrar Produto</a>
					<a class="<?php if($url == 'cadastrar-usuario') echo 'select';?>" href="cadastrar-usuario"> Cadastrar Usuário</a>

				</section><!--list-->
			</div><!--wraper-menu-->
		</div><!--menu-->

		<!-- Scripts -->
		
		<base base="<?php echo INCLUDE_PATH; ?>" />
		<script src="<?php echo INCLUDE_PATH; ?>js/jquery.js"></script>
		<script src="<?php echo INCLUDE_PATH; ?>js/mask.js"></script>
		<?php if ($url == 'cadastrar-pedido' 
				|| $url == 'cadastrar-produto' 
				|| $url == 'cadastrar-cliente' 
				|| $url == 'buscar-pedido' 
				|| $url == 'buscar-cliente'){?>
			<script src="<?php echo INCLUDE_PATH.'js/'.$url.'.js'; ?>"></script>
		<?php } ?>
		<script src="<?php echo INCLUDE_PATH; ?>js/constants.js"></script>
		<script src="<?php echo INCLUDE_PATH; ?>js/functions.js"></script>
		<?php if ($url == 'desempenho'){?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
			<script src="<?php echo INCLUDE_PATH; ?>js/desempenho.js"></script>
		<?php } ?>
		<?php if ($url == 'editar-cliente' || $url == 'editar-produto'){?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
			<script src="<?php echo INCLUDE_PATH; ?>js/lineChart.js"></script>
		<?php } ?>
		<script src="https://kit.fontawesome.com/4053268ba0.js" crossorigin="anonymous"></script>
		<!--  -->
	</body>
</html>
		