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
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
                            <input type="text" name="email" id="inputEmail" class="form-control" placeholder="Email " required="required">
                            <label for="inputEmail">Email </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" name="password" autocomplete="off" id="inputPassword" class="form-control" placeholder="Password" required="required">
                                    <label for="inputPassword">Password</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" id="confirmPassword" autocomplete="off" class="form-control" placeholder="Confirm password" required="required">
                                    <input type="hidden" name="token" value="<?php echo $token; ?>" />
                                    <label for="confirmPassword">Conferma la password</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"> Registrati </button>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
                    $conn = new mysqli("localhost", "root", "", "civicsense");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
                    $password = isset($_POST['password']) ? $_POST['password'] : null;
                    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null;

                    if ($email && $password && $password === $confirmPassword) {
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                        $query = "UPDATE team SET password = ? WHERE email_t = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('ss', $hashedPassword, $email);
                        $stmt->execute();

                        if ($stmt->affected_rows > 0) {
                            echo ("<br><b><br><p><center><font color='white' font face='Courier'>Password registrata! Clicca su <a href='login.php'> Login </a> per accedere.</b></center></p><br><br>");
                        } else {
                            echo ("<br><b><br><p><center><font color='white' font face='Courier'>Couldn't update data. Make sure that the email is correct and that the password is different from your current one.</b></center></p><br><br>");
                        }
                    } else {
                        echo ("<br><b><br><p><center><font color='white' font face='Courier'>Password and Confirm Password do not match or email is invalid.</b></center></p><br><br>");
                    }

                    $stmt->close();
                    $conn->close();
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
