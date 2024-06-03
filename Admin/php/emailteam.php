<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\Exception.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\PHPMailer.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\SMTP.php';

$env = parse_ini_file('../.env');

$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

$id = (isset($_POST['id'])) ? $_POST['id'] : null;
$team = (isset($_POST['team'])) ? $_POST['team'] : null;


if (isset($_POST['submit'])) {

	if ($id && $team !== null) {

		$resultC = mysqli_query($conn, "SELECT * FROM segnalazioni WHERE gravita IS NOT NULL AND team IS NULL AND id = $id");
		if ($resultC) {
			$row = mysqli_fetch_assoc($resultC);
			if ($id == $row['id']) {
				$query = ("UPDATE segnalazioni SET team = $team, stato = 'In attesa' WHERE id = $id ");

				$result = mysqli_query($conn, $query);
				if ($result) {

					echo ('<center><b>Aggiornamento avvenuto con successo.</b></center>');
					$mail = new PHPMailer(true);

					try {
						$query1 = ("SELECT * FROM team WHERE codice = $team");
						$result1 = mysqli_query($conn, $query1);
						if ($result1) {
							$row = mysqli_fetch_assoc($result1);
							$mail->isSMTP();
							$mail->SMTPAuth = true;                  // sblocchi SMTP 
							$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 // metti prefisso per il server
							$mail->Host = "smtp-mail.outlook.com";      // metti il tuo domino es(gmail) 
							$mail->Port = 587;   				// inserisci la porta smtp per il server DOMINIO
							$mail->SMTPKeepAlive = true;
							$mail->Username = $env['OUTLOOK_MAIL'];     // DOMINIO username
							$mail->From = $env['OUTLOOK_MAIL'];     // DOMINIO username
							$mail->Password = $env['OUTLOOK_PASSWORD'];            // DOMINIO password

							$mail->addAddress($row["email_t"]);
							$mail->setFrom($env['OUTLOOK_MAIL']);
							$mail->isHTML(true);
							$mail->Subject = 'Nuova Segnalazione';
							$mail->Body = "Salve team$team, vi e' stata incaricata una nuova segnalazione da risolvere."; //Messaggio da inviare
							if (!$mail->send()) {
								echo 'Messaggio non inviato: ' . $mail->ErrorInfo;
							} else {
								echo "<center><b>Messaggio inviato.</b></center>";
							}
							$mail->smtpClose();
						}
					} catch (Exception $e) {
						echo $e->errorMessage(); //Errori da PHPMailer
					} catch (\Exception $e) {
						echo $e->getMessage(); //Errori da altrove
					}
				}
			} else {
				echo "<center><b>Inserisci un id esistente. </b></center>";
			}
		}



	} else {
		echo "<center><b>Inserire tutti i campi.</b></center>";
	}
}

?>