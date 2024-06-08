<?php
session_start();
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
  <title>SB Admin - Login</title>
  <link href="Admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="Admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="Admin/css/sb-admin.css" rel="stylesheet">
</head>
<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form action="login.php" method="POST">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required="required" autofocus="autofocus">
              <label for="inputEmail">Email</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="inputPassword" autocomplete="off" name="password" class="form-control" placeholder="Password" required="required">
              <input type="hidden" name="token" value="<?php echo $token; ?>" />
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me"> Ricordami
              </label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
          <br>
          <center>
            <a class="d-block small mt-3" href="registrateam.php">Sei un nuovo team? Registra la tua password!</a>
          </center>
        </form>
      </div>
    </div>
  </div>
  <script src="Admin/vendor/jquery/jquery.min.js"></script>
  <script src="Admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="Admin/vendor/jquery-easing/jquery.easing.min.js"></script>
  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $env = parse_ini_file('../.env');
    $conn = new mysqli("localhost", "root", $env['DB_EMPTY_PASSWORD'], "civicsense");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['email']) && isset($_POST['password'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];

      // Check if user is admin
      $admin_query = "SELECT * FROM admin WHERE email = ? AND password = ?";
      $admin_stmt = $conn->prepare($admin_query);
      $admin_stmt->bind_param('ss', $email, $password);
      $admin_stmt->execute();
      $admin_result = $admin_stmt->get_result();
      $admin_row = $admin_result->fetch_assoc();

      if ($admin_row) {
        $_SESSION['isLogin'] = true;
        $_SESSION['isAdmin'] = true;
        header("Location: http://localhost/Ingegneria/admin");
        exit();
      } else {
        // Check if user is team member
        $team_query = "SELECT * FROM team WHERE email_t = ? AND password = ?";
        $team_stmt = $conn->prepare($team_query);
        $team_stmt->bind_param('ss', $email, $password);
        $team_stmt->execute();
        $team_result = $team_stmt->get_result();
        $team_row = $team_result->fetch_assoc();

        if ($team_row) {
          $_SESSION['isLogin'] = true;
          $_SESSION['isAdmin'] = false;
          header("Location: http://localhost/Ingegneria/team");
          exit();
        } else {
          $_SESSION['isLogin'] = false;
          echo '<div class="alert alert-danger">Accesso negato alla sezione riservata.</div>';
        }
      }

      $admin_stmt->close();
      $team_stmt->close();
    }

    $conn->close();
  }
  ?>
</body>
</html>
