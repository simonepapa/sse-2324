<?php
$conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");

mysqli_select_db($conn, "civicsense") or die("DataBase non trovato"); #connessione al db



$quer = mysqli_query($conn, "SELECT * FROM segnalazioni ");

function sanitize_content($conn, $content)
{
  $cont = stripslashes($content);
  $cont = strip_tags($cont);
  $cont = mysqli_real_escape_string($conn, $cont);
  $cont = htmlentities($cont);

  return $cont;
}


while ($row = mysqli_fetch_assoc($quer)) {
  echo "
    <tr>
     
                <td>" . sanitize_content($conn, $row['id']) . " <br></td>
                
                <td>" . sanitize_content($conn, $row['datainv']) . " <br></td> 
                
              <td>" . sanitize_content($conn, $row['orainv']) . "<br></td>

               <td>" . sanitize_content($conn, $row['via']) . "<br></td>

                <td>" . sanitize_content($conn, $row['descrizione']) . "<br></td>

                 <td>" . sanitize_content($conn, $row['foto']) . "<br></td>

                  <td>" . sanitize_content($conn, $row['email']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['stato']) . "<br></td>

                    <td>" . sanitize_content($conn, $row['team']) . "<br></td>

                   <td>" . sanitize_content($conn, $row['gravit�']) . "<br></td>
               
          </tr> ";
}
?>