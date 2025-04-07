<?php
session_start();

if (!isset($_SESSION["username"])) {
    echo "Devi essere loggato per visualizzare il profilo.";
    exit();
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profilo</title>
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
<div class="container">
        <h2>Profilo</h2>
        <?php
        $todsFile = "tods_" . $_SESSION["username"] . ".txt";

if (file_exists($todsFile)) {
    $righe = file($todsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "<h2>Storico Calcoli</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Reddito</th><th>Mensilità</th><th>Regione</th><th>Figli</th><th>Da Pagare</th><th>Netto</th></tr>";
    foreach ($righe as $riga) {
        list($reddito, $mensilita, $regione, $figli, $importoPagare, $netto) = explode("|", $riga);
        echo "<tr>
                <td>€$reddito</td>
                <td>$mensilita</td>
                <td>$regione</td>
                <td>$figli</td>
                <td>€$importoPagare</td>
                <td>€$netto</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nessun calcolo effettuato finora.</p>";
}?>
        <form method="post">
            <input type="text" name="username" placeholder="Nome utente" required>
            <input type="password" name="password" placeholder="Password" required> <br>
        </form>
        <p>vuoi tornare al calcolatore? <a href="index.php">Calcolatore Tasse</a></p>
    </div>
</body>
</html>