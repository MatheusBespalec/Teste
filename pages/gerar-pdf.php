<?php 

	ob_start();
	include('pedido.php');
	$content = ob_get_contents();
	ob_end_clean();

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML('<h1>Teste</h1>');
	$mpdf->Output();

?>