<?php
	//Recupero dati
	if(isset($_POST['email']) && isset($_POST['password'])){
		$email = $_POST['email'];
		$password = $_POST['password'];
		if($email == "civicsense18@gmail.com")
		{
			if($password == "admin")
			{
				echo 'Accesso consentito alla sezione riservata';
			}
			else
			{
				echo 'Accesso negato alla sezione riservata.La password � errata!';
			}
		}
		else
		{
			//Connessione Database
			$conn = mysqli_connect ("localhost", "root", "") or die ("Connessione non riuscita"); 
	        mysqli_select_db ($conn,"civicsense") or die ("DataBase non trovato"); #connessione al db
			
			$cod=mysqli_real_escape_string($conn,$email);
			$cod=stripslashes($email);

			$sql = 'SELECT * FROM team WHERE email_t = ?';
			$stmt=mysqli_prepare($conn,$sql);
			$stmt->bind_param('s',$email);
			$stmt->execute();
			$result = $stmt->get_result();	

			if (mysqli_num_rows($result) > 0) {
	   
	    		while($row = mysqli_fetch_assoc($result)) 
				{
					if($password != $row["password"] || $email != $row["email_t"])
					{
						//CODICE JAVASCRIPT
						echo 'ATTENZIONE: La password o la email inserita non � corretta!';
					}
					else if ($password == $row["password"] || $email == $row["email_t"]){
						echo 'Accesso consentito area riservata (TEAM)';
					}
			
				}
			}
			mysqli_close($conn);
		}
	}
	else{
		echo 'Non esistono;';
	}
	
	
		
?>