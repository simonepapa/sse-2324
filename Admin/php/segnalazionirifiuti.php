<?php
$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

$quer = mysqli_query($conn, "SELECT * FROM segnalazioni WHERE tipo = '2' ");


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