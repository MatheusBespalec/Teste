$(function(){

	//Grafico de linha(Vendas Timestre)
	var vendasAno = [];
	for (var i = 0; i < 12; i++) {
		vendasAno[i] = $('vendasano').attr('mes'+i);
	}

	dataLinha = {
	    // Legendas das Linhas
	    labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
	    datasets: [{
	        // Legenda
	        label: 'Vendas por Mês(Em R$)',
	        // Define-se a cor da linha.
	        borderColor: '#3ba6ed',
	        // Dados a serem inseridos nas barras
	        data: [vendasAno[0], 
			        vendasAno[1],
			        vendasAno[2], 
			        vendasAno[3], 
			        vendasAno[4], 
			        vendasAno[5], 
			        vendasAno[6], 
			        vendasAno[7], 
			        vendasAno[8], 
			        vendasAno[9], 
			        vendasAno[10], 
			        vendasAno[11]]
	    }]
	}

	let ctx = document.getElementById('vendasAno');
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

})