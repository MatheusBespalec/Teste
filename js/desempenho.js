$(function(){

	//Grafico de linha(Vendas Timestre)
	var vendasTrimestre = [];
	for (var i = 0; i < 12; i++) {
		vendasTrimestre[i] = $('vendatrimestre').attr('semana'+i);
	}

	dataLinha = {
	    // Legendas das Linhas
	    labels: ['', '', '', '', '', '', '', '', '', '', '', ''],
	    datasets: [{
	        // Legenda
	        label: 'Vendas do Trimestre (Por Semana)',
	        // Define-se a cor da linha.
	        borderColor: '#3ba6ed',
	        // Dados a serem inseridos nas barras
	        data: [vendasTrimestre[11], 
			        vendasTrimestre[10],
			        vendasTrimestre[9], 
			        vendasTrimestre[8], 
			        vendasTrimestre[7], 
			        vendasTrimestre[6], 
			        vendasTrimestre[5], 
			        vendasTrimestre[4], 
			        vendasTrimestre[3], 
			        vendasTrimestre[2], 
			        vendasTrimestre[1], 
			        vendasTrimestre[0]]
	    }]
	}

	let ctx = document.getElementById('vendasTrimeste');
	let chart = new Chart(ctx, {
	    type: 'line',
	    data: dataLinha,
	    options: {
		    elements: {
		        line: {
		            tension: 0
		        }
		    }
		}
	});

	//Grafico de Pizza(top 5 Clientes)

	var clienteTotal = [];
	var clienteNome = [];
	var clienteSoma = 0;

	for (var i = 0; i >= 0; i++) {
		if($('topclientes').attr('clienteNome'+i) == undefined)
			break;

		clienteNome[i] = $('topclientes').attr('clienteNome'+i);
		clienteTotal[i] = $('topclientes').attr('clienteTotal'+i);
	}

	for (var i = 5; i < clienteNome.length; i++) {
		clienteSoma+= parseInt(clienteTotal[i]);
	}

	let dataPizza = {
	    datasets: [{
	        // cria-se um vetor data, com os valores a ser dispostos no gráfico
	        data: [clienteTotal[0], clienteTotal[1], clienteTotal[2], clienteTotal[3], clienteTotal[4],clienteSoma],
	        // cria-se uma propriedade para adicionar cores aos respectivos valores do vetor data
	        backgroundColor: ['#ffaa33', '#a0ff33', '#33fffc','#b433ff','#ff3333']
	    }],
	    // cria-se legendas para os respectivos valores do vetor data
	    labels: [clienteNome[0], clienteNome[1], clienteNome[2], clienteNome[3], clienteNome[4],'Outros']
	};

	let clientes = document.getElementById('rankingClientes');
	let meuDonutChart = new Chart(clientes, {
	    type: 'pie',
	    data: dataPizza,
	    options: {
	    	cutoutPercentage: 10,
		}
	});

	//Grafico de Pizza(top 5 Produtos)

	var produtoTotal = [];
	var produtoNome = [];
	var produtoSoma = 0;

	for (var i = 0; i >= 0; i++) {
		if($('topprodutos').attr('produtoNome'+i) == undefined)
			break;

		produtoNome[i] = $('topprodutos').attr('produtoNome'+i);
		produtoTotal[i] = $('topprodutos').attr('produtoTotal'+i);
	}

	for (var i = 5; i < produtoNome.length; i++) {
		produtoSoma+= parseInt(produtoTotal[i]);
	}

	let dataPizza2 = {
	    datasets: [{
	        // cria-se um vetor data, com os valores a ser dispostos no gráfico
	        data: [produtoTotal[0], produtoTotal[1], produtoTotal[2], produtoTotal[3], produtoTotal[4],produtoSoma],
	        // cria-se uma propriedade para adicionar cores aos respectivos valores do vetor data
	        backgroundColor: ['#ffaa33', '#a0ff33', '#33fffc','#b433ff','#ff3333']
	    }],
	    // cria-se legendas para os respectivos valores do vetor data
	    labels: [produtoNome[0], produtoNome[1], produtoNome[2], produtoNome[3], produtoNome[4],'Outros']
	};

	let produtos = document.getElementById('rankingProdutos');
	let meuPieChart = new Chart(produtos, {
	    type: 'pie',
	    data: dataPizza2,
	    options: {
	    	cutoutPercentage: 10,
		}
	});

})