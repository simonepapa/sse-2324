<?php session_start();
  if(!isset($_SESSION['isLogin'])  
    || (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] !== true)
  ){ //if login in session is not set
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

      <a href="index.php" class="navbar-brand me-1 fw-bold"> Area riservata</a>

      <a href="profile.php" class="ms-5 text-white text-decoration-none">Profile</a>
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


    <div id="wrapper">

          <div class="card mb-3">
           
			
			<!-- MAPPA -->
	
  <style>
    #map{
      height:500px;
      width:100%; 
      margin-left:0%;
    }
    *{
      margin:0;
      padding:0;
    }
  </style>


  <div id="map"></div>
    
    <script>
      function initMap(){
      var location = new google.maps.LatLng(40.382003, 17.367155);
      var map = new google.maps.Map(document.getElementById("map"),{
        zoom:18,
        center:location
      });
      <?php 
      if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
        $conn = mysqli_connect("localhost","root","","civicsense") or die("Connessione fallita");

        if(isset($_SESSION['idT'])){

        $sql = "SELECT * FROM segnalazioni WHERE team = ?";
		    $stmt = $mysqli->prepare($sql);
		    $stmt->bind_param('i', $_SESSION['idT']);
		    $stmt->execute();
		    $resultC = mysqli_stmt_get_result($stmt);

        if($resultC){
          while($row=mysqli_fetch_assoc($resultC)){

            echo "
            var location = new google.maps.LatLng(".sanitize_content($conn, $row['latitudine']).",".sanitize_content($conn, $row['longitudine']).");
            var marker = new google.maps.Marker({
              map: map,
              position: location
            }); " ;
          }
          mysqli_close($conn);
        }
      }
      }
        
      ?>
      /*var marker = new google.maps.Marker({
              map: map,
              position: location
          });
      var marker1 = new google.maps.Marker({
        map: map,
        position: location1
      });*/
      
    }
    
    </script>
    
      
  <script async defer 
  src="https://maps.googleapis.com/maps/api/js?key=<?php echo $env['GOOGLE_MAPS_API_KEY']; ?>&callback=initMap">
    </script>
  



			
			<!-- FINE MAPPA -->






			<br><br><br>
			
			 <div class="card-header">
              <i class="fas fa-table"></i>
             Tabella Segnalazioni da risolvere</div>
			 <br><br>
            <div class="card-body">
			 <!-- Tabella -->
              <div class="table-responsive" style="overflow-x: scroll;" >
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" >
                  <thead >
                    <tr>
                      <th>CODICE SEGNALAZIONE</th>
                      <th>DATA</th>
                      <th>ORA</th>
                      <th>VIA</th>
                      <th>DESCRIZIONE</th>
                      <th>FOTO</th>
					            <th>TIPO</th>
                      <th>STATO</th>
					            <th>GRAVITA'</th>
                    </tr>
                  </thead>

<?php include("php/segnalazione.php"); ?>


</table>








	<!-- MODIFICA STATO SEGNALAZIONE -->
	
<!-- inserimento da form del codice della segnalazione da modificare -->
<br><br><br>

<div class="card-header">
  <i class="fas fa-table"></i>
Modifica stato di una segnalazione</div>

	<form  method="post" action ="modifiche.php" style=" margin-top:5%; margin-left:5%">
<b>CODICE SEGNALAZIONE DA MODIFICARE: <input type="text" name="id"><br><br></b>
<b> INSERISCI LO STATO MODIFICATO: </b> <select class="text" name="stato"> 
   
    <option value="Risolto">Risolto</option>
    <option value="In risoluzione">In risoluzione</option>

<input type="submit"  class="btn btn-primary btn-block" style="width:15%; margin-top:5%;">
<input type="hidden" name="token" value="<?php echo $token; ?>" />

    </form>

<br><br><br>


<br><br>
</div></div></div>

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
