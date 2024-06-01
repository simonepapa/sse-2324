<?php
$conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");

mysqli_select_db($connect, "civicsense") or die("DataBase non trovato"); #connessione al db

if (isset($_SESSION['idT'])) {
  $upload_path = '../Admin/img/';


  $team = (isset($_POST['team'])) ? $_POST['team'] : null;

  function sanitize_content($conn, $content)
  {
    $cont = stripslashes($content);
    $cont = strip_tags($cont);
    $cont = mysqli_real_escape_string($conn, $cont);
    $cont = htmlentities($cont);

    return $cont;
  }


  $quer = mysqli_query($conn, "SELECT * FROM segnalazioni WHERE stato  <> 'Risolto' AND team = " . sanitize_content($conn, $_SESSION['idT']));


  while ($row = mysqli_fetch_assoc($quer)) {
    echo "
    <tr>
     
                <td>" . sanitize_content($conn, $row['id']) . " <br></td>
                
                <td>" . sanitize_content($conn, $row['datainv']) . " <br></td> 
                
              <td>" . sanitize_content($conn, $row['orainv']) . "<br></td>

               <td>" . sanitize_content($conn, $row['via']) . "<br></td>

                <td>" . sanitize_content($conn, $row['descrizione']) . "<br></td>

                 <td><img width='200px' height='200px' src=" . $upload_path . sanitize_content($conn, $row['foto']) . "><br></td>
				  
				   <td>" . sanitize_content($conn, $row['tipo']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['stato']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['gravita']) . "<br></td>
               
          </tr> ";
  }



}



?>