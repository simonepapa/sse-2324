<?php session_start()?>
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
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required="required" autofocus="autofocus">
                <label for="inputEmail"> Email </label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="required">
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
			
         <button type="submit" class="btn btn-primary btn-block" > Login</button> 
		 <br>
		 <center> <a class="d-block small mt-3" href="registrateam.php">Sei un nuovo team? Registra la tua password!</a> </center>
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
  if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if($email == "civicsense2019@gmail.com")
    {
      if($password == "admin")
      {
        echo 'Accesso consentito alla sezione riservata';
        echo '<script>window.location.href = "index.php";</script>';

      }
      else
      {
        echo 'Accesso negato alla sezione riservata.La password è errata!';
      }
    }
    else
    {
      //Connessione Database
     $conn = mysql_connect ("localhost", "root", "") or die ("Connessione non riuscita"); #connessione a mysql, la pass non la ho xk è scaricato automaticamente

mysql_select_db ("civicsense") or die ("DataBase non trovato"); #connessione al db



      $sql = "SELECT * FROM team ";
     
$result = mysql_query($sql);
    


if (mysql_num_rows($result) > 0) {
	
    while($row = mysql_fetch_assoc($result))
        {
          if($password != $row["password"] || $email != $row["email_t"])
          {
            //CODICE JAVASCRIPT
            echo 'ATTENZIONE: La password o la email inserita non è corretta!';
          }
          else if ($password == $row["password"] || $email == $row["email_t"]){
				$_SESSION['email']=$email;
				$_SESSION['pass']=$password;
				$_SESSION['idT']=$row['codice'];  
				echo 'Accesso consentito area riservata (TEAM)';
				header("location: http://localhost//Ingegneria/Team/index.php");
          }
      
        }
}
      

    }
  
 
  
  }
    
?>
