<?php

$env = parse_ini_file('../.env');


$conn = mysqli_connect("localhost", "root", $env['DB_EMPTY_PASSWORD']) or die("Connessione non riuscita");

mysqli_select_db ($conn,"civicsense") or die ("DataBase non trovato"); 


$id = (isset($_POST['id'])) ? $_POST['id'] : null;
$stato = (isset($_POST['stato'])) ? $_POST['stato'] : null;


if ($id && $stato !== null) {

 $query = "UPDATE segnalazioni SET stato = ? WHERE id = ? ";
 $stmt=mysqli_prepare($conn,$query);
 $stmt->bind_param('si',$stato,$id);
 $stmt->execute();
 $result = $stmt->get_result();	

if($result){
	echo("<br><b><br><p> <center> <font color=black font face='Courier'> Inserimento avvenuto correttamente! Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
} 
}

?>
	