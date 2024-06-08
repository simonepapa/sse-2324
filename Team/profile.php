<?php session_start();
if (
  !isset($_SESSION['isLogin'])
  || (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] !== true)
) { //if login in session is not set
  header("Location: http://localhost/Ingegneria/login.php");
}
?>
<?php
$env = parse_ini_file('../.env');
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Tables</title>

  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/datatables.min.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <!-- grafico -->
  <link rel="stylesheet" href="css/graficostyle.css">


</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a href="index.php" class="navbar-brand me-1" href=""> Area riservata</a>

    <a href="profile.php" class="ms-5 text-white text-decoration-none fw-bold">Profile</a>
    <a href="privacy.php" class="ms-5 text-white text-decoration-none">Privacy Consent</a>


    <!-- INIZIO LOGOUT -->

    <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
      <ul class="navbar-nav ms-auto ms-md-0">
        <li class="nav-item dropdown no-arrow dropstart">
          <a class="nav-link dropdown-toggle" href="#" title="Logout" id="userDropdown" role="button"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal"> Logout </button>
          </div>
        </li>
      </ul>

    </div>
  </nav>

  <!-- finestra avviso-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler lasciare il sito?</h5>
          <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Clicca "Logout" per uscire dal sito.</div>
        <form action="php/logout.php" method="POST" class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Annulla</button>
          <button type="submit.php" class="btn btn-primary">Logout</button>
          <input type="hidden" name="token" value="<?php echo $token; ?>" />
        </form>
      </div>
    </div>
  </div>

  <!-- FINE LOGOUT-->


  <div id="wrapper" class="d-flex flex-column justify-content-center">

    <h2 class="mt-5 mb-1" style="text-align:center;">Change password</h2>
    <form action="profile.php" method="POST" class="mx-auto d-flex flex-column gap-4" style="width: 400px">
      <div class="form-group">
        <div class="form-label-group">
          <input type="password" name="currentPassword" autocomplete="off" id="currentPassword" class="form-control"
            placeholder="Enter your current password" required="required">
          <label for="currentPassword">Current password</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-row row">
          <div class="col-6">
            <div class="form-label-group">
              <input type="password" name="password" autocomplete="off" id="inputPassword" class="form-control"
                placeholder="Enter your new password" required="required">
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-label-group">
              <input type="password" name="confirmPassword" autocomplete="off" id="confirmPassword" class="form-control"
                placeholder="Confirm your new password" required="required">
              <label for="confirmPassword">Confirm password</label>
              <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block"> Change password </button>
      <br>
    </form>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\Exception.php';
    require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\PHPMailer.php';
    require 'C:\xampp\htdocs\Ingegneria\Admin\phpmailer\SMTP.php';

    $env = parse_ini_file('../.env');

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
    ?>
  </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/datatables.min.js"></script>


  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>












</body>

</html>