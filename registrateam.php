<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];
?>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Register</title>

  <!-- Bootstrap core CSS-->
  <link href="Admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="Admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="Admin/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Registra la password del team</div>
      <div class="card-body">
        <form action="registrateam.php" method="POST">

          <div class="form-group">
            <div class="form-label-group">
              <input type="text" name="email" id="inputEmail" class="form-control" placeholder="Email "
                required="required">
              <label for="inputEmail">Email </label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <div class="form-label-group">
                  <!-- VULNERABILITY: Autocomplete -->
                  <input type="password" name="password" autocomplete="off" id="inputPassword" class="form-control"
                    placeholder="Password" required="required">
                  <label for="inputPassword">Password</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-label-group">
                  <!-- VULNERABILITY: Autocomplete -->
                  <input type="password" id="confirmPassword" name="confirmPassword" autocomplete="off" class="form-control"
                    placeholder="Confirm password" required="required">
                  <input type="hidden" name="token" value="<?php echo $token; ?>" />
                  <label for="confirmPassword">Conferma la password</label>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block"> Registrati </button>
          <br>
          <center> <a class="d-block small mt-3" href="login.php">Hai già un team? Effettua il login!</a> </center>
        </form>
      </div>
    </div>
  </div>


  <?php
  if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");
    mysqli_select_db($conn, "civicsense") or die("DataBase non trovato");


    $email = (isset($_POST['email'])) ? mysqli_real_escape_string($conn, $_POST['email']) : null;
    $pass = (isset($_POST['password'])) ? mysqli_real_escape_string($conn, $_POST['password']) : null;
    $confirmPass = (isset($_POST['confirmPassword'])) ? mysqli_real_escape_string($conn, $_POST['confirmPassword']) : null;

    $lengthPattern = '/.{8,}/';
    $numberSymbolPattern = '/(?=.*[0-9])|(?=.*[^A-Za-z0-9])/';
    $uppercasePattern = '/(?=.*[A-Z])/';

    $lengthCheck = preg_match($lengthPattern, $pass);
    $numberSymbolCheck = preg_match($numberSymbolPattern, $pass);
    $uppercaseCheck = preg_match($uppercasePattern, $pass);

    echo $pass;
    echo $confirmPass;

    if ($lengthCheck && $numberSymbolCheck && $uppercaseCheck && $pass == $confirmPass) {
      $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

      if ($email && $pass !== null) {
        $query = ("INSERT INTO team(email_t, password) VALUES (?, ?)");

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $email, $hashed_password);
        $result = mysqli_stmt_execute($stmt);
        if (mysqli_affected_rows($conn) > 0) {
          echo ("<br><b><br><p> <center> <font color='white' font face='Courier'> Team registrato! Clicca su <a href='login.php'> Login </a> per accedere. </b></center></p><br><br> ");
        } else {
          echo ("<br><b><br><p> <center> <font color='white' font face='Courier'> Oops! Something went wrong.</b></center></p><br><br> ");
        }
      }
    } else {
      echo ("<p> <center> <font color='white' font face='Courier'> Couldn't register user. Make sure that both password are the same, that the password is at least of 8 characters and contains at least an uppercase character, a number and a special character </b></center></p><br><br> ");

    }


  }


  ?>


  <!-- Bootstrap core JavaScript-->
  <script src="Admin/vendor/jquery/jquery.min.js"></script>
  <script src="Admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="Admin/vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>