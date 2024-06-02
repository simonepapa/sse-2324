<?php
session_start();
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];
?>
<!DOCTYPE html>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Login</title>

  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form action="#" method="POST">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email"
                required="required" autofocus="autofocus">
              <label for="inputEmail"> Email </label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <!-- VULNERABILITY: Autocomplete -->
              <input type="password" id="inputPassword" autocomplete="false" name="password" class="form-control" placeholder="Password"
                required="required">
              <label for="inputPassword"> Password </label>
            </div>
          </div>
          <input type="hidden" name="token" value="<?php echo $token; ?>" />
          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me">
                Ricordami
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block"> Login</button>
          <br>
          <center> <a class="d-block small mt-3" href="registrateam.php">Sei un nuovo team? Registra la tua
              password!</a> </center>
        </form>

      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <?php
  //Recupero dati
  if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];
      if ($email == "civicsense2019@gmail.com") {
        if ($password == "admin") {
          echo 'Accesso consentito alla sezione riservata';
          echo '<script>window.location.href = "index.php";</script>';

        } else {
          echo 'Accesso negato alla sezione riservata.La password è errata!';
        }
      } else {

        /* Connessione Database
        
        $conn = mysqli_connect("localhost", "root", $env['DB_EMPTY_PASSWORD']) or die("Connessione non riuscita"); #connessione a mysql, la pass non la ho xk è scaricato automaticamente
  
        mysqli_select_db($conn, "civicsense") or die("DataBase non trovato"); #connessione al db
  


        $sql = "SELECT * FROM team ";

        $result = mysqli_query($conn, $sql);
*/

 // Database connection using mysqli to make it more efficient
 $env = parse_ini_file('../.env');
  $conn = new mysqli("localhost", "root", $env['DB_EMPTY_PASSWORD'], "civicsense");

 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }

 // Prepare and bind
 $stmt = $conn->prepare("SELECT * FROM team WHERE email_t = ?");
 $stmt->bind_param("s", $email);
 $stmt->execute();
 $result = $stmt->get_result();

  /*      if (mysql_num_rows($result) > 0) {

          while ($row = mysqli_fetch_assoc($result)) {
            if ($password != $row["password"] || $email != $row["email_t"]) {
              //CODICE JAVASCRIPT
              echo 'ATTENZIONE: La password o la email inserita non è corretta!';
            } else if ($password == $row["password"] || $email == $row["email_t"]) {
              $_SESSION['email'] = $email;
              $_SESSION['pass'] = $password;
              $_SESSION['idT'] = $row['codice'];
              echo 'Accesso consentito area riservata (TEAM)';
              header("location: http://localhost//Ingegneria/Team/index.php");
            }

          }
        }


      }


*/
    }
  }

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verifying the password
    if (password_verify($password, $row["password"])) {
        $_SESSION['email'] = $email;
        $_SESSION['idT'] = $row['codice'];
        echo 'Accesso consentito area riservata (TEAM)';
        header("Location: http://localhost/Ingegneria/Team/index.php");
        exit();
    } else {
        echo 'ATTENZIONE: La password inserita non è corretta!';
     }
  }
 else {
    echo 'ATTENZIONE: La email inserita non è corretta!';
    }
  }

$stmt->close();
$conn->close();

  ?>
