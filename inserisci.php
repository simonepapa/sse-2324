<?php session_start();
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
  <link href="/Ingegneria/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="/Ingegneria/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="/Ingegneria/admin/vendor/datatables/datatables.min.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/Ingegneria/admin/css/sb-admin.css" rel="stylesheet">

  <!-- Grafico -->
  <link rel="stylesheet" href="/Ingegneria/admin/css/graficostyle.css">

</head>

<body id="page-top">


  <div class="card-header ms-4 mt-2">
    <i class="fas fa-table"></i>
    inserisci segnalazione

  </div>

  <!-- <form method="post" action="inserisci.php" style=" margin-top:5%; margin-left:5%;">-->
  <form method="post" action="inserisci.php" style=" margin-top:16px; margin-left:5%;" enctype="multipart/form-data">
    <b>DATA INVIO: <input type="date" name="data"><br><br></b>
    <b> ORA INVIO: </b> <input type="time" name="ora"><br><br></b>
    <b> VIA (VIA NOMEVIA, N CIVICO, CAP, PROVINCIA (ES: PULSANO O TARANTO), TA, ITALIA: )<input type="text"
        name="via"><br><br></b>
    <b> DESCRIZIONE: <input type="text" name="descr"><br><br></b>
    <!-- VULNERABILITY: File upload -->
    <b> FOTO: <input type="file" name="foto" accept="image/png, image/jpg, image/jpeg"><br><br></b>
    <b> EMAIL (LA VOSTRA): <input type="email" name="email"><br><br></b>
    <b> LATITUDINE: <input type="text" name="lat"><br><br></b>
    <b> LONGITUDINE: <input type="text" name="long"><br><br></b>
    <b> TIPOLOGIA: </b> <select class="text" name="tipo">

      <option value="1">SEGNALAZIONI AREE VERDI</option>
      <option value="2">RIFIUTI E PULIZIA STRADALE</option>
      <option value="3">STRADE E MARCIAPIEDI</option>
      <option value="4">SEGNALETICA E SEMAFORI</option>
      <option value="4">ILLUMINAZIONE PUBBLICA</option>
    </select>
    <div class="form-group mt-4">
      <div class="form-row row">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="privacyConsent" value="" id="privacyConsent">
          <input type="hidden" name="privacyConsentHidden" id="privacyConsentHidden" value="0" />
          <label class="form-check-label" for="privacyConsent">
            I explicitly give my consent for the use of my data as described in the <a target="_blank"
              href="privacy-policy.php">Privacy Policy</a>
          </label>
        </div>
      </div>
    </div>
    <input type="submit" name="submit" class="btn btn-primary btn-block" style="width:15%; margin-top:3%;">
    <input type="hidden" name="token" value="<?php echo $token; ?>" />
  </form>

  <?php
  // INSTEAD OF THIS LET TRY TO CHANGE THE CODE FOR THE POSSIBLE VULNERABILITIES 
  /*$conn = mysqli_connect ("localhost","id8503350_civicsense","civicsense","id8503350_civicsense") or die ("Connessione non riuscita"); 

 $data = (isset($_POST['data'])) ? $_POST['data'] : null;
 $ora = (isset($_POST['ora'])) ? $_POST['ora'] : null;
 $via = (isset($_POST['via'])) ? $_POST['via'] : null;
 $descr = (isset($_POST['descr'])) ? $_POST['descr'] : null;
 $foto = (isset($_POST['foto'])) ? $_POST['foto'] : null;
 $email = (isset($_POST['email'])) ? $_POST['email'] : null;
 $lat = (isset($_POST['lat'])) ? $_POST['lat'] : null;
 $long = (isset($_POST['long'])) ? $_POST['long'] : null;
 $tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : null;

 $finfo = finfo_open(FILEINFO_MIME_TYPE);
 foreach (glob("*") as $filename) {
   // VULNERABILITY: File upload
   // If MIME type is not allowed
   if (!in_array(finfo_file($finfo, $filename), ['image/png', 'image/jpg', 'image/jpeg'])) {
     finfo_close($finfo);
     return;
   }
 }
 finfo_close($finfo);

$data=mysqli_real_escape_string($conn,$data);
$data=stripslashes($data);
$ora=mysqli_real_escape_string($conn,$ora);
$ora=stripslashes($ora);
$via=mysqli_real_escape_string($conn,$via);
$via=stripslashes($via);
$email=mysqli_real_escape_string($conn,$email);
$email=stripslashes($email);
$lat=mysqli_real_escape_string($conn,$lat);
$lat=stripslashes($lat);
$long=mysqli_real_escape_string($conn,$long);
$long=stripslashes($long);



 $sql = "INSERT INTO segnalazioni
           (datainv, orainv, via, descrizione, foto, email, tipo, latitudine, longitudine)
           VALUES
           ('$data','$ora', '$via', '$descr', '$foto', '$email', '$tipo', '$lat', '$long') ";
       $result = mysqli_query($conn,$sql);

 if ($result) {
   echo "<center> inserimento avvenuto. </center>";

 }*/

  //THE CORRECT CODE IS DOWN AND MORE SECURE
  if (!empty($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = isset($_POST['data']) ? mysqli_real_escape_string($conn, stripslashes($_POST['data'])) : null;
      $ora = isset($_POST['ora']) ? mysqli_real_escape_string($conn, stripslashes($_POST['ora'])) : null;
      $via = isset($_POST['via']) ? mysqli_real_escape_string($conn, stripslashes($_POST['via'])) : null;
      $descr = isset($_POST['descr']) ? mysqli_real_escape_string($conn, stripslashes($_POST['descr'])) : null;
      $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, stripslashes($_POST['email'])) : null;
      $lat = isset($_POST['lat']) ? mysqli_real_escape_string($conn, stripslashes($_POST['lat'])) : null;
      $long = isset($_POST['long']) ? mysqli_real_escape_string($conn, stripslashes($_POST['long'])) : null;
      $tipo = isset($_POST['tipo']) ? mysqli_real_escape_string($conn, stripslashes($_POST['tipo'])) : null;
      $privacyConsent = isset($_POST['privacyConsentHidden']) ? $_POST['privacyConsentHidden'] == '0' ? 0 : 1 : 0;

      // Handling file upload securely
      $allowed_mime_types = ['image/png', 'image/jpg', 'image/jpeg'];
      $file_tmp_path = $_FILES['foto']['tmp_name'];
      $file_mime_type = mime_content_type($file_tmp_path);

      if ($_FILES['foto']['error'] == UPLOAD_ERR_OK && in_array($file_mime_type, $allowed_mime_types)) {
        $foto = file_get_contents($file_tmp_path);
      } else {
        $foto = null;
        echo "<center>Formato del file non supportato.</center>";
      }

      if ($data && $ora && $via && $descr && $email && $lat && $long && $tipo && $foto) {
        $sql = "INSERT INTO segnalazioni (datainv, orainv, via, descrizione, foto, email, tipo, latitudine, longitudine, privacy_consent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssiddi', $data, $ora, $via, $descr, $foto, $email, $tipo, $lat, $long, $privacyConsent);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
          echo "<center>Inserimento avvenuto.</center>";
        } else {
          echo "<center>Errore durante l'inserimento: " . mysqli_error($conn) . "</center>";
        }

        mysqli_stmt_close($stmt);
      } else {
        echo "<center>Tutti i campi sono obbligatori.</center>";
      }
    }

    mysqli_close($conn);
  }

  ?>

  <script>
    document.getElementById("privacyConsent").addEventListener("change", (e) => {
      document.getElementById("privacyConsentHidden").value = e.target.checked === true ? '1' : '0'
    })
  </script>

</body>