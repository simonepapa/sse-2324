<?php
// The all codes has to be changed to fix all possible all SQL injection vulnerabilities 
/*
$conn = mysqli_connect("localhost", "root", "") or die("Connessione non riuscita");
mysqli_select_db($conn, "civicsense") or die("DataBase non trovato");
$id = $_POST['id'];
$team = $_POST['team'];
if ($id && $team !== null) {
    $query = ("SELECT email_t FROM team WHERE codice = '$team'"); //possible unnecessary sanitization
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo ('<a href="mailto: ' . sanitize_content($conn, $result) . '"><center> Clicca qui per mandare un avviso al team. </center></a>');
    }
} */


$conn = mysqli_connect("localhost", "root", "", "civicsense") or die("Connessione non riuscita: " . mysqli_connect_error());

$id = isset($_POST['id']) ? $_POST['id'] : null;
$team = isset($_POST['team']) ? $_POST['team'] : null;

if ($id !== null && $team !== null) {
    // Prepare the query using prepared statements
    $query = "SELECT email_t FROM team WHERE codice = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $team);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $email_t);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    // Check if a valid email address was retrieved
    if ($email_t !== null) {
        // Output the email address as a mailto link
        echo '<a href="mailto:' . $email_t . '"><center> Clicca qui per mandare un avviso al team. </center></a>';
    } else {
        echo "Team non trovato.";
    }
}

// Close the connection
mysqli_close($conn);

?> 