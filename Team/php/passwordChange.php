<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\Exception.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\PHPMailer.php';
require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\SMTP.php';

$env = parse_ini_file('../../.env');

if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");
    mysqli_select_db($conn, "civicsense") or die("DataBase non trovato");

    $email = $_SESSION['email'];
    $currentPass = (isset($_POST['currentPassword'])) ? mysqli_real_escape_string($conn, $_POST['currentPassword']) : null;
    $pass = (isset($_POST['password'])) ? mysqli_real_escape_string($conn, $_POST['password']) : null;
    $confirmPass = (isset($_POST['confirmPassword'])) ? mysqli_real_escape_string($conn, $_POST['confirmPassword']) : null;

    $lengthPattern = '/.{8,}/';
    $numberSymbolPattern = '/(?=.*[0-9])|(?=.*[^A-Za-z0-9])/';
    $uppercasePattern = '/(?=.*[A-Z])/';

    $lengthCheck = preg_match($lengthPattern, $pass);
    $numberSymbolCheck = preg_match($numberSymbolPattern, $pass);
    $uppercaseCheck = preg_match($uppercasePattern, $pass);

    if ($pass == $confirmPass) {
        $checkPwQuery = ("SELECT * FROM team WHERE email_t = ?");

        $checkPw_stmt = mysqli_prepare($conn, $checkPwQuery);
        $checkPw_stmt->bind_param('s', $email);
        $checkPw_stmt->execute();
        $checkPw_result = $checkPw_stmt->get_result();

        $row = mysqli_fetch_assoc($checkPw_result);
        if ($row !== null && $email === $row['email_t'] && password_verify($currentPass, $row['password'])) {
            $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
            $query = ("UPDATE team SET password = ? WHERE email_t = ?");

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $email);
            $result = mysqli_stmt_execute($stmt);
            if (mysqli_affected_rows($conn) > 0) {
                echo ("<b><p> <center> <font font face='Courier'>Password changed succesfully. You will receive an email shortly as a reminder.</b></center></p><br><br> ");
                $mail = new PHPMailer(true);

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

                    $mail->addAddress($row["email_t"]);
                    $mail->setFrom($env['OUTLOOK_MAIL']);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your password changed';
                    $mail->Body = "Hi team " . $row['email_t'] . ", your password has been changed."; //Messaggio da inviare
                    if (!$mail->send()) {
                        echo 'Messaggio non inviato: ' . $mail->ErrorInfo;
                    } else {
                        echo "<center><b><a href='../profile.php'>Go back to profile.</a></b></center>";
                    }
                    $mail->smtpClose();
                } catch (Exception $e) {
                    echo $e->errorMessage(); //Errori da PHPMailer
                } catch (\Exception $e) {
                    echo $e->getMessage(); //Errori da altrove
                }
            } else {
                echo ("<b><p> <center> <font font face='Courier'> Oops! Something went wrong. Make sure that the password are the same and that the current password is correct</b></center></p><br><br> ");
            }
        } else {
            echo ("<b><p> <center> <font font face='Courier'> Oops! Something went wrong. Make sure that the new passwords are the same and that the current password is correct</b></center></p> ");
            if (!$lengthCheck || !$numberSymbolCheck || !$uppercaseCheck) {
                echo "<p class='text-center'>Your password should match the following conditions:</p><ul class='mx-auto' style='width: fit-content;'>
          <li style='" . ($lengthCheck == 1 ? 'color: #8FE7A8' : 'color: #FF8389') . "'>at least 8 characters</li>
          <li style='" . ($numberSymbolCheck == 1 ? 'color: #8FE7A8' : 'color: #FF8389') . "'>contains a number or symbol</li>
          <li style='" . ($uppercaseCheck == 1 ? 'color: #8FE7A8' : 'color: #FF8389') . "'>contains at least an uppercase character</li>
        </ul>";
            }

        }
    }
}