
<?php

$conn = mysqli_connect ("localhost", "root", "") or die ("Connessione non riuscita"); 

mysqli_select_db ($conn,"civicsense") or die ("DataBase non trovato"); 


$id = $_POST['id'];
$team = $_POST['team'];


if ($id && $team !== null) {



 $query = ("SELECT email_t FROM team WHERE codice = '$team'"); 


$result = mysqli_query($conn,$query);	


if ($result){



echo('<a href="mailto: '.$result.'"><center> Clicca qui per mandare un avviso al team. </center></a>');

}
}
?>