<?php 
	
	//Verificar se esta logado
	if(!isset($_SESSION['login']) || $_SESSION['login'] == false)
		header('Location: pages/permissao-negada.php');

	//Pegar vendas de cada uma das ultimas 12 semanas
	$total = [];

	for ($i = 0; $i < 12; $i++) { 
		$n1 = $i*7;
		$n2 = $n1+6;
		
		$data1 = date('Y-m-d', strtotime('-'.$n1.' days'));
		$data2 = date('Y-m-d', strtotime('-'.$n2.' days'));

		$pedidos = Painel::selectAll('tb_admin.pedidos','WHERE data BETWEEN \''.$data2.'\' AND \''.$data1.'\'',[]);
		if(count($pedidos) > 0){
			foreach($pedidos as $key => $value){
				@$total[$i]+= $value['lucro_bruto'];
			}
		}else{
			@$total[$i]+= 0;
		}

	}	

	//Top 5 clientes
	$clientes = Painel::selectAll('tb_admin.clientes','',[]);
	$rankClientes = [];
	foreach ($clientes as $key => $value) {
		$rankClientes[$value['nome']] = $value['vendas'];
	}
		
	arsort($rankClientes);

	//Top 5 produtos
	$produtos = Painel::selectAll('tb_admin.itens','',[]);
	$rankProdutos = [];
	foreach ($produtos as $key => $value) {
		$rankProdutos[$value['nome']] = $value['vendas'];
	}
		
	arsort($rankProdutos);

?>
<div class="box-content">

	<h2><i class="fas fa-chart-line"></i> Gráficos de Desempenho</h2>
	
	<h3>Venas do Trimeste</h3>
	<vendatrimestre
		<?php foreach($total as $key => $value){ 
			 echo 'semana'.$key.'="'.$value.'" '; 
		 } ?>
	/>
	<div class="wraper-chart">
		<div class="chart">
			<canvas id="vendasTrimeste"></canvas>
		</div>
	</div><!--wraper-chart-->

	<h3>Top 5 Clientes</h3>
	<topclientes
		<?php foreach($rankClientes as $key => $value){ 
			static $count = 0;
			echo 'clienteNome'.$count.'="'.$key.'" '; 
			echo 'clienteTotal'.$count.'="'.$value.'" '; 
			$count++;
		 } ?>
	/>
	<div class="wraper-chart">
		<div class="chart">
			<canvas id="rankingClientes"></canvas>
		</div>
	</div><!--wraper-chart-->

	<h3>Top 5 Produtos</h3>
	<topprodutos
		<?php foreach($rankProdutos as $key => $value){ 
			static $count2 = 0;
			echo 'produtoNome'.$count2.'="'.$key.'" '; 
			echo 'produtoTotal'.$count2.'="'.$value.'" '; 
			$count2++;
		 } ?>
	/>
	<div class="wraper-chart">
		<div class="chart">
			<canvas id="rankingProdutos"></canvas>
		</div>
	</div><!--wraper-chart-->
	
</div><!--box-content-->