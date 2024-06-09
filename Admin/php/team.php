<?php
$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita");

$sql = mysqli_query($conn, "SELECT * FROM team");

if (!function_exists('sanitize_content')) {
  function sanitize_content($conn, $content)
  {
    $cont = stripslashes($content);
    $cont = strip_tags($cont);
    $cont = mysqli_real_escape_string($conn, $cont);
    $cont = htmlentities($cont);

    return $cont;
  }
}


// output data of each row
while ($row = mysqli_fetch_assoc($sql)) {
  echo "
		<tr>
                <td>" . sanitize_content($conn, $row['codice']) . " </td>
                
                <td>" . sanitize_content($conn, $row['email_t']) . "</td> 
                
              <td>" . sanitize_content($conn, $row['nomi']) . "</td>
               
          </tr> ";
}
?>