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

$env = parse_ini_file('../.env');

//$conn = mysqli_connect ("localhost", "root", "","civicsense") or die ("Connessione non riuscita"); 

$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita: " . mysqli_connect_error());

if (isset($_POST['id']) && isset($_POST['stato'])) {

	$stato = mysqli_real_escape_string($conn, $_POST['stato']); // Sanitize input	

	$query = "SELECT * FROM segnalazioni WHERE id =? ";
	$stmt = mysqli_prepare($conn, $query);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = mysqli_stmt_get_result($stmt);


	if ($result && mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_assoc($result);
		if ($row['stato'] == "In attesa" && $stato == "In risoluzione") { //confronta stato attuale e quello da modificare
			$sql = "UPDATE segnalazioni SET stato = ? WHERE id = ?"; //esegui l'aggiornamento
			$stmt = mysqli_prepare($conn, $query);
			$stmt->bind_param('si', $stato, $id);
			$stmt->execute();
			$result = mysqli_stmt_get_result($stmt);

			//if($query){
			if ($result) {
				echo ("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
				//$mail = new PHPMailer(true);

				try {
					$mail->isSMTP();
					$mail->SMTPAuth = true;                  // sblocchi SMTP 
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 // metti prefisso per il server
					$mail->Host = "smtp-mail.outlook.com";      // metti il tuo domino es(gmail) 
					$mail->Port = 587;   				// inserisci la porta smtp per il server DOMINIO
					$mail->SMTPKeepAlive = true;
					$mail->Username = $env['OUTLOOK_MAIL'];     // DOMINIO username
					$mail->From = $env['OUTLOOK_MAIL'];     // DOMINIO username
					$mail->Password = $env['OUTLOOK_PASSWORD'];            // DOMINIO password
					$mail->addAddress($_SESSION["email"]);
					$mail->setFrom($env['OUTLOOK_MAIL']);
					$mail->isHTML(true);
					$mail->Subject = 'Nuova Segnalazione';
					$mail->Body = "Salve team {$row['team']}, ci è arrivata una nuova segnalazione e vi affido il compito di risolverla"; //Messaggio da inviare
					if (!$mail->send()) {
						echo 'Messaggio non inviato: ' . $mail->ErrorInfo;
					} else {
						echo "<center><b>Messaggio inviato.</b></center>";
					}
					$mail->smtpClose();
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
			$stmt = mysqli_prepare($conn, $query);
			$stmt->bind_param('si', $stato, $id);
			$stmt->execute();
			$result = mysqli_stmt_get_result($stmt);



			//if($query){
			if ($result) {
				echo ("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
				//$mail = new PHPMailer(true);

				try {
					$mail = new PHPMailer(true);
					$mail->SMTPAuth = true;                  // sblocchi SMTP 
					$mail->SMTPSecure = "ssl";                 // metti prefisso per il server
					$mail->Host = "smtp.gmail.com";      // metti il tuo domino es(gmail) 
					$mail->Port = 465;   				// inserisci la porta smtp per il server DOMINIO
					$mail->SMTPKeepAlive = true;
					$mail->Mailer = "smtp";
					$mail->Username = "$_SESSION[email]";  			// DOMINIO username
					$mail->Password = "$_SESSION[pass]";            // DOMINIO password
					$mail->AddAddress('civicsense18@gmail.com');//ente
					//$mail->AddAddress("$row['email']");//utente
					$mail->AddAddress($row['email']); // Utente
					$mail->SetFrom("$_SESSION[email]");
					$mail->Subject = "Segnalazione risolta";
					//$mail->Body = "Il problema presente in $row['via'] è stata risolta"; //Messaggio da inviare
					$mail->Body = "Il problema presente in {$row['via']} è stata risolta"; //Messaggio da inviare
					$mail->Send();
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
	} else {
		echo " Nessuna segnalazione trovata con l'ID specificato";
	}
	mysqli_close($conn);
}

?>