<?php
$conn = mysql_connect ("localhost", "root", "") or die ("Connessione non riuscita"); 

mysql_select_db ("civicsense") or die ("DataBase non trovato"); #connessione al db

if(isset($_SESSION['idT'])){
	$upload_path = '../Admin/img/';
  

$team = (isset($_POST['team'])) ? $_POST['team'] : null;



    $quer = mysql_query ("SELECT * FROM segnalazioni WHERE stato  <> 'Risolto' AND team = ".$_SESSION['idT'] );
  

    while($row = mysql_fetch_assoc($quer)) {
        echo "
    <tr>
     
                <td>".$row['id']." <br></td>
                
                <td>".$row['datainv']." <br></td> 
                
              <td>".$row['orainv']."<br></td>

               <td>".$row['via']."<br></td>

                <td>".$row['descrizione']."<br></td>

                 <td><img width='200px' height='200px' src=".$upload_path.$row['foto']."><br></td>
				  
				   <td>".$row['tipo']."<br></td>

                   <td>".$row['stato']."<br></td>

                   <td>".$row['gravita']."<br></td>
               
          </tr> ";
    }



  }



?>