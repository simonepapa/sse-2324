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
  <link href="Admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="Admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="Admin/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form action="#" method="POST" class="d-flex flex-column gap-4">
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
              <input type="password" id="inputPassword" autocomplete="off" name="password" class="form-control"
                placeholder="Password" required="required">
              <input type="hidden" name="token" value="<?php echo $token; ?>" />
              <label for="inputPassword"> Password </label>
            </div>
          </div>

          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me">
                Ricordami
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block"> Login</button>
          <a class="d-block small text-center" href="registrateam.php">Sei un nuovo team? Registra la tua
              password!</a>
        </form>

      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="Admin/vendor/jquery/jquery.min.js"></script>
  <script src="Admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="Admin/vendor/jquery-easing/jquery.easing.min.js"></script>

  <?php
  //Recupero dati
  if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $env = parse_ini_file('.env');
    $conn = new mysqli("localhost", "root", $env['DB_EMPTY_PASSWORD'], "civicsense");

    if (isset($_POST['email']) && isset($_POST['password'])) {
      $email = $_POST['email'];
      $password = $_POST['password']; 
      $admin_query = "SELECT * FROM admin WHERE email = ?";

      $admin_stmt = mysqli_prepare($conn, $admin_query);
      $admin_stmt->bind_param('s', $email);
      $admin_stmt->execute();
      $admin_result = $admin_stmt->get_result();

      $row = mysqli_fetch_assoc($admin_result);
      //
      if ($row !== null && $row['email'] === $email && password_verify($password, $row['password'])) {
        $_SESSION['isLogin'] = true;
        $_SESSION['isAdmin'] = true;
        $_SESSION['email'] = $email;
        header("Location: http://localhost/Ingegneria/admin");
      } else {
        $team_query = "SELECT * FROM team WHERE email_t = ?";

        $team_stmt = mysqli_prepare($conn, $team_query);
        $team_stmt->bind_param('s', $email);
        $team_stmt->execute();
        $team_result = $team_stmt->get_result();

        $team_row = mysqli_fetch_assoc($team_result);
        if ($team_row !== null && $team_row['email_t'] === $email && password_verify($password, $team_row['password'])) {
          $_SESSION['isLogin'] = true;
          $_SESSION['isAdmin'] = false;
          $_SESSION['email'] = $email;
          header("Location: http://localhost/Ingegneria/team");
        } else {
          $_SESSION['isLogin'] = false;
          echo 'Accesso negato alla sezione riservata.';
        }
      }
    }
    $conn->close();
  }
  ?>