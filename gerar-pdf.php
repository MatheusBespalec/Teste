<?php 

	$url = explode('/', $_GET['url'])[1];

	ob_start();

?>
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

		<title>Gerar PDF</title>

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
	<body style="background-color: #fff;">
	<?php 

		include('pdf/'.$url.'.php');
		$content = ob_get_contents();
		ob_end_clean();

		require 'vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($content);
		$mpdf->Output();

	?>
	</body>
</html>