<?php
$conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");

mysqli_select_db($conn, "civicsense") or die("DataBase non trovato"); #connessione al db

$quer = mysqli_query($conn, "SELECT * FROM segnalazioni WHERE tipo = '3' ");


while ($row = mysqli_fetch_assoc($quer)) {
  echo "
    <tr>
     
                <td>" . sanitize_content($conn, $row['id']) . " <br></td>
                
                <td>" . sanitize_content($conn, $row['datainv']) . " <br></td> 
                
              <td>" . sanitize_content($conn, $row['orainv']) . "<br></td>

               <td>" . sanitize_content($conn, $row['via']) . "<br></td>

                <td>" . sanitize_content($conn, $row['descrizione']) . "<br></td>

                <td><img width='200px' height='200px' src=data:image/jpeg;base64," . sanitize_content($conn, base64_encode($row['foto'])) . "><br></td>

                  <td>" . sanitize_content($conn, $row['email']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['stato']) . "<br></td>

                    <td>" . sanitize_content($conn, $row['team']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['gravita']) . "<br></td>
               
          </tr> ";
}
?>