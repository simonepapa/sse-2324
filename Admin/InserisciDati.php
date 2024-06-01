<?php
$conn = new MySQLi("localhost", "root", "", "civicsense");

$upload_path = 'jpeg/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$file_path = $upload_path . $_FILES['image']['name'];
	$img_name = $_FILES['image']['name'];
	$email = $_POST['email'];
	$tipo = $_POST['tipo'];
	if ($tipo == "Segnalazione di area verde") {
		$tipo = 1;
	} else if ($tipo == "Rifiuti e pulizia stradale") {
		$tipo = 2;
	} else if ($tipo == "Strade e marciapiedi") {
		$tipo = 3;
	} else if ($tipo == "Segnaletica e semafori") {
		$tipo = 4;
	} else if ($tipo == "Illuminazione pubblica") {
		$tipo = 5;
	}
	$via = $_POST['via'];
	$descrizione = $_POST['descrizione'];
	$lat = $_POST['latitudine'];
	$lat = floatval($lat);
	$lng = $_POST['longitudine'];
	$lng = floatval($lng);

	$sanitized_file_path = preg_replace("/[^a-zA-Z0-9]+/", "", $file_path);

	try {
		move_uploaded_file($_FILES['image']['tmp_name'], $sanitized_file_path);
		$query = "INSERT INTO `segnalazioni`(`datainv`, `orainv`, `via`, `descrizione`, `foto`, `email`,`tipo`,`latitudine`,`longitudine`) 
			VALUES (CURRENT_DATE,CURRENT_TIME,?,?,?,?,?,?,?)";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('ssbsidd', $via, $descrizione, $img_name, $email, $tipo, $lat, $lng);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result) {
			echo "Inserimento dei dati completato";
		} else {
			echo "Errore nell'inserimento dei dati";
		}

	} catch (Exception $e) {
		$e->getMessage();
	}
	$conn->close();
}

?>