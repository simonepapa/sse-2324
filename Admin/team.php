<?php session_start();
  if(!isset($_SESSION['isLogin']) 
    || !isset($_SESSION['isAdmin']) 
    || (isset($_SESSION['isLogin']) && isset($_SESSION['isAdmin']) && ($_SESSION['isLogin'] !== true | $_SESSION['isAdmin'] !== true))
  ){ //if login in session is not set
    header("Location: http://localhost/Ingegneria/login.php");
}
?>
<!DOCTYPE html>
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

  <title>SB Admin - Tables</title>

  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/datatables.min.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">



</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand me-1" href=""> Area riservata</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
    <div class="titolo"> <b> GESTIONE TEAM </b> </a>

      <style>
        .titolo {
          font-size: 30px;
          color: white;
          margin-left: 30%;
        }
      </style>
    </div>

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

  <div id="wrapper">



    <!-- INIZIO SIDEBAR -->

    <ul class="sidebar navbar-nav">
      <br>
      <li class="nav-item dropdown">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Home</span>
        </a>
      </li>


      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="pagesDropdown" role="button" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false" href="#">
          <i class="fas fa-fw fa-folder"></i>
          <span>Segnalazioni</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="segnalazionii.php">
            <div style="text-align: center; font-weight: bold; font-size: 24px; margin-top: 20px;">INDICE SEGNALAZIONI
            </div>
          </a>
          <a class="dropdown-item" href="segnalazioniverde.php">Segnalazione su aree verdi</a>
          <a class="dropdown-item" href="segnalazionirifiuti.php">Rifiuti e pulizia stradale</a>
          <a class="dropdown-item" href="segnalazionistrade.php">Strade e marciapiedi</a>
          <a class="dropdown-item" href="segnalazionisemafori.php">Segnaletica e semafori</a>
          <a class="dropdown-item" href="segnalazioniilluminazione.php"> Illuminazione pubblica </a>
        </div>
      </li>

      <li class="nav-item active">
        <a class="nav-link " href="team.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Team</span>
        </a>
      </li>

    </ul>

    <!-- FINE SIDEBAR -->


    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-table"></i>
        Tabella team
      </div>
      <div class="card-body">

        <div class="table-responsive" style="overflow-x: scroll;">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>CODICE TEAM</th>
                <th>E-MAIL</th>
                <th>COMPONENTI</th>
              </tr>
            </thead>

            <?php include ("php/team.php"); ?>


          </table>


          <br><br><br>

          <!-- TABELLA SEGNALAZIONI SENZA TEAM -->



          <div class="card-header">
            <i class="fas fa-table"></i>
            Segnalazioni senza team
          </div>

          <br><br><br>
          <!-- Tabella -->
          <div class="table-responsive" style="overflow-x: scroll; ">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>CODICE SEGNALAZIONE</th>
                  <th>VIA</th>
                  <th>GRAVITA'</th>
                  <th>TIPO</th>
                </tr>
              </thead>

              <?php include ("php/team2.php"); ?>


            </table>


            <!-- MODIFICA TEAM -->

            <br><br><br>

            <div class="card-header">
              <i class="fas fa-table"></i>
              Assegna una segnalazione ad un team
            </div>

            <form method="post" action="team.php" style=" margin-top:5%; margin-left:5%">
              <b>CODICE SEGNALAZIONE: <input type="text" name="id"><br><br></b>
              <b>SELEZIONA L'EMAIL DEL TEAM: </b> <select class="text" name="team">

                <?php
                if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
                  $conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

                  $selezione = mysqli_query($conn, "SELECT email_t, codice FROM team") or die(mysqli_error($conn));

                  if ($selezione) {


                    while ($array = mysqli_fetch_assoc($selezione)) {
                      $email = sanitize_content($conn, $array["email_t"]);
                      $codice = sanitize_content($conn, $array["codice"]);


                      //da qui c'è il menù a discesa riempito con i valori del database
                      echo "

		<option value='$codice'>$email</option>

	";
                    }
                  }
                }

                ?>
                <input type="submit" name="submit" class="btn btn-primary btn-block" style="width:15%; margin-top:5%;">
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                <?php include ("php/emailteam.php"); ?>
              </select>
            </form>
            <br>


            <br><br>



            <!-- ELIMINA TEAM -->

            <div class="card-header">
              <i class="fas fa-table"></i>
              Elimina un team
            </div>

            <form method="post" action="team.php" style=" margin-top:5%; margin-left:5%">
              <b>CODICE TEAM DA ELIMINARE: <input type="text" name="cod"><br><br></b>
              <input type="submit" name="submit2" class="btn btn-primary btn-block" style="width:15%; margin-top:5%;">
              <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </form>
            <?php include ("php/cancellateam.php"); ?>

            <br><br><br>




            <!-- INSERIMENTO TEAM -->

            <div class="card-header">
              <i class="fas fa-table"></i>
              Inserisci un nuoto team
            </div>

            <form method="post" action="team.php" style=" margin-top:5%; margin-left:5%">
              <b>E-MAIL TEAM:</b> <input type="email" name="email"><br><br>
              <b>NOMI E COGNOMI DEI COMPONENTI:</b> <input type="text" name="nomi"><br><br>
              <b>NUMERO DI COMPONENTI: </b> <input type="number" name="numero"><br><br>
              <!-- VULNERABILITY: Autocomplete -->
              <b>PASSWORD:</b> <input type="password" name="password" autocomplete="off"><br>

              <input type="submit" name="submit3" class="btn btn-primary btn-block" style="width:15%; margin-top:5%;">
              <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </form>


            <?php

            if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
              $conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");




              # ---INSERIMENTO DA FORM ---
            
              #salvo i nomi (name) dei form in una variabile php, richiamando i valori dal form con _POST (se nel fotm era 'method=get' diventava $_GET)
            


              $email = (isset($_POST['email'])) ? mysqli_real_escape_string($conn, $_POST['email']) : null;
              $nomi = (isset($_POST['nomi'])) ? mysqli_real_escape_string($conn, $_POST['nomi']) : null;
              $numeri = (isset($_POST['numero'])) ? $_POST['numero'] : null;
              $pass = (isset($_POST['password'])) ? mysqli_real_escape_string($conn, $_POST['password']) : null;

              if (isset($_POST['submit3'])) {
                if ($email && $nomi && $numeri && $pass !== null) {
                  #inserisco i valori salvati dal form nella query di inserimento
            
                  $toinsert = "INSERT INTO team
                (email_t, npersone, nomi, password)
                VALUES
                (?, ?, ?, ?)";

                  $stmt = mysqli_prepare($conn, $toinsert);
                  $stmt->bind_param('siss', $email, $numeri, $nomi, $pass);
                  $stmt->execute();
                  $result = mysqli_stmt_get_result($stmt);

                  if ($result) {
                    echo ("<b><br><p> <center> <font color=black font face='Courier'> Inserimento avvenuto correttamente! Ricarica la pagina per vedere la tabella aggiornata!</p></b></center>");
                  }
                } else {
                  echo ("<p> <center> <font color=black font face='Courier'>Compila tutti i campi.</p></b></center>");
                }
              }
            }



            ?>



















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