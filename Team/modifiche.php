<?php
session_start();
//puoi modificare la pagina per farla funzionare nella tua macchina
//adatto a tutti i domini (GMAIL,LIBERO.HOTMAIL)
//classi per l'invio dell'email (PHPMailer 5.2)


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\Exception.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\PHPMailer.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\SMTP.php';

$conn = new mysqli("localhost", "root", "", "civicsense") or die("Connessione non riuscita");



if (isset($_POST['id']) && isset($_POST['stato'])) {
	$idS = $_POST['id'];
	$stato = $_POST['stato'];
	$email = $_SESSION['email'];
	$pass = $_SESSION['pass'];

	$query = "SELECT * FROM segnalazioni WHERE id = ? ";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('i', $idS);
	$stmt->execute();
	$result = $stmt->get_result();

	$result = $conn->query($query);


	if ($result) {
		//da team a ente e utente
		$row = $result->fetch_assoc();
		if ($row['stato'] == "In attesa" && $stato == "In risoluzione") { //confronta stato attuale e quello da modificare
			$sql = "UPDATE segnalazioni SET stato = ? WHERE id = ?"; //esegui l'aggiornamento
			$stmt = mysqli_prepare($conn, $sql);
			$stmt->bind_param('si', $stato, $idS);
			$stmt->execute();
			$result1 = $stmt->get_result();

			$result1 = $conn->query($sql);
			if ($result1) {
				echo ("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
				$mail = new PHPMailer(true);

				try {
					$mail->isSMTP();
					$mail->SMTPAuth = true;                  // sblocchi SMTP 
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 // metti prefisso per il server
					$mail->Host = "smtp-mail.outlook.com";      // metti il tuo domino es(gmail) 
					$mail->Port = 587;   				// inserisci la porta smtp per il server DOMINIO
					$mail->SMTPKeepAlive = true;
					$mail->Username = "$email";     // DOMINIO username
					$mail->From = "$email";     // DOMINIO username
					$mail->Password = "$pass";            // DOMINIO password
					$mail->addAddress($row["email"]);
					$mail->setFrom("$email");
					$mail->isHTML(true);
					$mail->Subject = 'Nuova Segnalazione';
					$mail->Body = "La segnalazione è arrivata ed stiamo lavorando per risolverla"; //Messaggio da inviare
					if (!$mail->send()) {
						echo 'Messaggio non inviato: ' . $mail->ErrorInfo;
					} else {
						echo "<center><b>Messaggio inviato.</b></center>";
					}
					$mail->smtpClose();
					header("location: http://localhost/Ingegneria/Team/index.php");
					echo "Message Sent OK";
				} catch (Exception $e) {
					echo $e->errorMessage(); //Errori da PHPMailer
				} catch (\Exception $e) {
					echo $e->getMessage(); //Errori da altrove
				}
			}
		}
		//da team a ente e utente
		else if ($row['stato'] == "In risoluzione" && $stato == "Risolto") {
			$sql = "UPDATE segnalazioni SET stato = ? WHERE id = ?"; //esegui l'aggiornamento
			$stmt = mysqli_prepare($conn, $sql);
			$stmt->bind_param('si', $stato, $idS);
			$stmt->execute();
			$result1 = $stmt->get_result();


			$result1 = $conn->query($sql);
			if ($result1) {
				echo ("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
				$mail = new PHPMailer(true);

				try {
					$mail->isSMTP();
					$mail->SMTPAuth = true;                  // sblocchi SMTP 
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 // metti prefisso per il server
					$mail->Host = "smtp-mail.outlook.com";      // metti il tuo domino es(gmail) 
					$mail->Port = 587;   				// inserisci la porta smtp per il server DOMINIO
					$mail->SMTPKeepAlive = true;
					$mail->Username = "$email";     // DOMINIO username
					$mail->From = "$email";     // DOMINIO username
					$mail->Password = "$pass";            // DOMINIO password
					$mail->addAddress($row["email"]);
					$mail->setFrom("$email");
					$mail->isHTML(true);
					$mail->Subject = "Segnalazione risolta";
					$mail->Body = "Il problema presente in " . $row['via'] . " è stata risolta"; //Messaggio da inviare
					if (!$mail->send()) {
						echo 'Messaggio non inviato: ' . $mail->ErrorInfo;
					} else {
						echo "<center><b>Messaggio inviato.</b></center>";
					}
					$mail->smtpClose();
					header("location: http://localhost/Ingegneria/Team/index.php");
					echo "Message Sent OK";
				} catch (Exception $e) {
					echo $e->errorMessage(); //Errori da PHPMailer
				} catch (\Exception $e) {
					echo $e->getMessage(); //Errori da altrove
				}



			}
		} else {
			echo "Operazione non disponibile";
		}
	}
	mysqli_close($conn);
}

?>