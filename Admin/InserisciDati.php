<?php
$env = parse_ini_file('../.env');
$conn = new MySQLi("localhost", "root", $env['DB_EMPTY_PASSWORD'], "civicsense");

$upload_path = 'jpeg/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sanitized_image_name = preg_replace("/[^a-zA-Z0-9]+/", "", $_FILES['image']['name']); // To put in DB
	$sanitized_imagetmp_name = preg_replace("/[^a-zA-Z0-9]+/", "", $_FILES['image']['tmp_name']); // To move file

	$file_path = $upload_path . $sanitized_image_name;
	$sanitized_file_path = preg_replace("/[^a-zA-Z0-9]+/", "", $file_path);

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

	try {
		// This file is not used
		// VULNERABILITY: File upload, Path Manipulation
		move_uploaded_file($sanitized_imagetmp_name, $sanitized_file_path);
		$query = "INSERT INTO `segnalazioni`(`datainv`, `orainv`, `via`, `descrizione`, `foto`, `email`,`tipo`,`latitudine`,`longitudine`) 
			VALUES (CURRENT_DATE,CURRENT_TIME,?,?,?,?,?,?,?)";
		$stmt = mysqli_prepare($conn, $query);
		//Bind parameters
		$stmt->bind_param('ssbsidd', $via, $descrizione, $sanitized_image_name, $email, $tipo, $lat, $lng);
		//Execute the statement
		$stmt->execute();

		
	               /*	$result = $stmt->get_result();
	                  	if ($result) {
	              		echo "Inserimento dei dati completato";
	                 	} else {
	                 		echo "Errore nell'inserimento dei dati";
	                     	}

                        	} catch (\Exception $e) {
                        		$e->getMessage();
	                              }
	                         $conn->close();
                               }

                        ?> */

// Check if the query was successful
if ($stmt->affected_rows > 0) {
	echo "Inserimento dei dati completato";
} else {
	echo "Errore nell'inserimento dei dati";
}

// Close the statement
$stmt->close();

} catch (\Exception $e) {
// Handle exceptions
echo "Errore: " . $e->getMessage();
}

// Close the connection
$conn->close();
}
?>