<?php
$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

$quer = mysqli_query($conn, "SELECT * FROM segnalazioni WHERE gravita IS NOT NULL AND team IS NULL");

function sanitize_content($conn, $content)
{
    $cont = stripslashes($content);
    $cont = strip_tags($cont);
    $cont = mysqli_real_escape_string($conn, $cont);
    $cont = htmlentities($cont);

    return $cont;
}

if (mysqli_num_rows($quer) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($quer)) {
        echo "
    <tr>
     
                <td>" . sanitize_content($conn, $row['id']) . " <br></td>
                
                <td>" . sanitize_content($conn, $row['via']) . " <br></td> 
                
              <td>" . sanitize_content($conn, $row['gravita']) . "<br></td>
			  
			    <td>" . sanitize_content($conn, $row['tipo']) . "<br></td>
               
          </tr> ";
    }
}
?>