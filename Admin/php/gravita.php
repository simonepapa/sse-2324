<?php
//Load environment variables
$env = parse_ini_file('../.env');

//Connessione al database
                    /*$conn = mysqli_connect ("localhost", "root", $env['DB_EMPTY_PASSWORD']) or die ("Connessione non riuscita"); 

                       mysqli_select_db ($conn,"civicsense") or die ("DataBase non trovato"); 


                      $id = (isset($_POST['id'])) ? $_POST['id'] : null;
                        $stato = (isset($_POST['stato'])) ? $_POST['stato'] : null;
                      */

  $conn = mysqli_connect("localhost", "root", $env['DB_EMPTY_PASSWORD'], "civicsense") or die("Connessione non riuscita: " . mysqli_connect_error());

	 // Initialize variables
	$id = isset($_POST['id']) ? intval($_POST['id']) : null;
	$stato = isset($_POST['stato']) ? intval($_POST['stato']) : null;
					  
	 // Check if the form was submitted


if (isset($_POST['submit'])){  

	//validate input

                    /*if ($id && $stato !== null) {

                  $query = ("UPDATE segnalazioni SET stato = $stato WHERE id = $id");

                   $result = mysqli_query($conn,$query);	

                  if($result){

	              while($row = mysqli_fetch_assoc($result)) {

		        if ($id == $row['id']){
	
	           echo("<br><b><br><p> <center> <font color=black font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br> ");
               } 
             else {

            echo ("INSERISCI UN ID ESISTENTE");
           }

            }
           }
             } */
	 if ($id !== null && $stato !== null) {
				// Prepare the query using prepared statements
				$query = "UPDATE segnalazioni SET stato = ? WHERE id = ?";
				$stmt = mysqli_prepare($conn, $query);
				
				// Bind parameters
				mysqli_stmt_bind_param($stmt, "ii", $stato, $id);
		
				// Execute the query
				mysqli_stmt_execute($stmt);
		
				// Check if the query was successful
				if (mysqli_stmt_affected_rows($stmt) > 0) {
					echo "<br><b><br><p> <center> <font color='black' font face='Courier'> Aggiornamento avvenuto correttamente. Ricarica la pagina per aggiornare la tabella.</b></center></p><br><br>";
				} else {
					echo "INSERISCI UN ID ESISTENTE";
				}
		
				// Close the statement
				mysqli_stmt_close($stmt);
			} else {
	           echo("inserisci tutti i campi");
           }
}
// Close the connection
mysqli_close($conn);

?>