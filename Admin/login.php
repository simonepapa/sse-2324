<?php
session_start();

// CSRF token generation
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
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="css/sb-admin.css" rel="stylesheet">
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
              <label for="inputEmail"> Email </label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="inputPassword" autocomplete="off" name="password" class="form-control" placeholder="Password" required="required">
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
          <button type="submit" class="btn btn-primary btn-block">Login</button>
          <br>
          <a class="d-block small mt-3 text-center" href="registrateam.php">Sei un nuovo team? Registra la tua password!</a>
        </form>

        <?php
        // Handling form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
              $email = $_POST['email'];
              $password = $_POST['password'];

              // Database connection
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
 $result = mysqli_stmt_get_result($stmt);

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
                  header("Location: index.php");
                  exit();
                } else {
                  echo '<div class="alert alert-danger">ATTENZIONE: La password inserita non è corretta!</div>';
                }
              } else {
                echo '<div class="alert alert-danger">ATTENZIONE: La email inserita non è corretta!</div>';
              }

              // Close connection
              $conn->close();
            }
          }
        }
        ?>

      </div>
    </div>
  </div>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>
</html>
