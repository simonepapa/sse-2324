<!DOCTYPE html>
<?php session_start()?>
<?php
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];
?>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin - Register</title>

  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

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
                  <input type="password" id="confirmPassword" autocomplete="off" class="form-control"
                    placeholder="Confirm password" required="required">
                  <input type="hidden" name="token" value="<?php echo $token; ?>" />
                  <label for="confirmPassword">Conferma la password</label>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block"> Registrati </button>

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


    if ($email && $pass !== null) {


      $query = ("UPDATE team SET password = ? WHERE email_t = ?");

      $stmt = mysqli_prepare($conn, $query);
      $stmt->bind_param('ss', $pass, $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result) {
        echo ("<br><b><br><p> <center> <font color=white font face='Courier'> Password registrata! Clicca su <a href='login.php'> Login </a> per accedere. </b></center></p><br><br> ");
      }
    }
  }


  ?>


  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>