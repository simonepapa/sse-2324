<?php
$conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");

mysqli_select_db($conn, "civicsense") or die("DataBase non trovato"); #connessione al db

if (isset($_SESSION['idT'])) {
  $team = (isset($_POST['team'])) ? $_POST['team'] : null;

  $sql = "SELECT * FROM segnalazioni WHERE stato  <> 'Risolto' AND team = ?";
  $stmt = mysqli_prepare($conn, $sql);
  $stmt->bind_param('i', $_SESSION['idT']);
  $stmt->execute();
  $resultC = mysqli_stmt_get_result($stmt);

  while ($row = mysqli_fetch_assoc($resultC)) {
    echo "
    <tr>
     
                <td>" . sanitize_content($conn, $row['id']) . " <br></td>
                
                <td>" . sanitize_content($conn, $row['datainv']) . " <br></td> 
                
              <td>" . sanitize_content($conn, $row['orainv']) . "<br></td>

               <td>" . sanitize_content($conn, $row['via']) . "<br></td>

                <td>" . sanitize_content($conn, $row['descrizione']) . "<br></td>

                <td><img width='200px' height='200px' src=data:image/jpeg;base64," . sanitize_content($conn, base64_encode($row['foto'])) . "><br></td>
				  
				        <td>" . sanitize_content($conn, $row['tipo']) . "<br></td>

                <td>" . sanitize_content($conn, $row['stato']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['gravita']) . "<br></td>
               
          </tr> ";
  }



}



?>