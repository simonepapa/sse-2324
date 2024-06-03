<?php
$mysqli = new mysqli("localhost", "root", "", "civicsense");
// Check connection
if ($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}

$cod = (isset($_POST['cod'])) ? $_POST['cod'] : null;

if (isset($_POST['submit2'])) {

	if ($cod == null) {
		echo ("<p> <center> <font color=black font face='Courier'> Compila tutti i campi.</center></p>");
	} elseif ($cod !== null) {
		$query = "SELECT * FROM team WHERE codice = ? ";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('i', $codice);
		$stmt->execute();
		$resultC = $stmt->get_result();


		if ($resultC) {
			$row = mysqli_fetch_assoc($resultC);
			if ($cod == $row['codice']) {
				$query = "DELETE FROM team WHERE codice = ? ";
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param('i', $codice);
				$stmt->execute();
				$resultC = $stmt->get_result();

				if ($query) {
					echo ("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
				}
			} else {
				echo ("<p> <center> <font color=black font face='Courier'> Inserisci ID esistente.</center></p>");
			}
		}
	}
}