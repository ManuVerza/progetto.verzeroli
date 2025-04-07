<?php
session_start();

// file di memorizzazione di tutti gli utenti
$usersFile = 'users.txt';

// Controllo se il form è stato inviato
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Controllo se la checkbox è selezionata
    $_SESSION['abbonamento'] = isset($_POST['abbonamento']) ? true : false;

    if (empty($username) || empty($password)) {
        echo "<p>Errore: Inserisci un nome utente e una password.</p>";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Controllo se l'utente esiste già
        $exists = false;
        if (file_exists($usersFile) && ($file = fopen($usersFile, "r"))) {
            while (($line = fgets($file)) !== false) {
                list($storedUsers, ) = explode('|', trim($line));
                if ($storedUsers == $username) {
                    $exists = true;
                    break;
                }
            }
            fclose($file);
        }

        if ($exists) {
            $_SESSION["messaggio"] = "Nome utente già esistente";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Scrittura nel file
            if ($file = fopen($usersFile, "a")) {
                fwrite($file, "$username|$passwordHash\n");
                fclose($file);
                $_SESSION["username"] = $username;
                header("Location: index.php");
                exit(); // Importante per evitare che il codice continui dopo il redirect
            } else {
                $_SESSION["messaggio"] = "Impossibile accedere al file utente";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    }
}

/*
session_start();

// file di memorizzazione di tutti gli utenti
$usersFile = 'users.txt';


//funzione per registrare un nuovo utente
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (isset($_POST['abbonamento'])) {
        $abbonamento = $_POST['abbonamento'];
        echo $abbonamento;
    }
}
if (empty($username) || empty($password)) {
    echo "<p>Errore: Inserisci un nome utente e una password.</p>";
} else {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    //controllo se l'utente esiste già
    $exists = false;
    if (file_exists($usersFile) && ($file = fopen($usersFile, "r"))) {//=> "r" lettura del file 
        while (($line = fgets($file)) != false) {
            list($storedUsers, ) = explode('|', trim($line));
            if ($storedUsers == $username) {
                $exists = true;
                break;
            }
        }
        fclose($file); //=> chiusura del file $usersFile
    }
    if ($exists) {
        echo "<p> ERRORE </p>";
        echo "<p> Nome utente già esistente </p>";
    } else {
        //scrittura nel file
        if ($file = fopen($usersFile, "a")) {//scrive nel file in cima 
            fwrite($file, "$username|$passwordHash\n");
            fclose($file);
            header("Location: index.html");
        } else {
            echo "<p>ERRORE</p>";
            echo "<p>Impossibile accedere al file utenti</p>";
        }
    }

}*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registrazione</title>
    <link rel="stylesheet" href="styleLogin.css">
</head>

<body>

    <script>
        function registraAbbonamento() {
            var abbonamento = document.getElementById("abbonamento").checked;
            var container2 = document.getElementById("container2");
            if (abbonamento) {
                container2.innerHTML = `
            <input type='text' name='nCarta' placeholder='Inserisci il numero di carta' required>
            <input type='text' name='CVV' placeholder='Inserisci il CVV' required>
             `;
                /*document.getElementById("container2").innerHTML = " <input type='text' name='nCarta' placeholder='Inserisci il numero di carta'>";
                document.getElementById("container2").innerHTML += " <input type='text' name='CVV' placeholder='inserisci il CVV'>";
            */} else {
                container2.innerHTML = "";
            }
        }

    </script>

    <div class="container">
        <?php
        if (isset($_SESSION['messaggio'])) {
            echo "<p> " . $_SESSION['messaggio'] . "</p>";
            unset($_SESSION["messaggio"]);
        }
        ?>
        <h2>Registrazione</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Nome utente" required>
            <input type="password" name="password" placeholder="Password" required><br>
            <label for="abbonameto">Vuoi abbonarti?</label>
            <input type="checkbox" name="abbonamento" id="abbonamento" value="1" onclick=registraAbbonamento()><br>
            <div id="container2">
            </div>
            <button type="submit" name="register">Registrati</button>
            <p>Hai già un account? <a href="login.php">Accedi</a></p>
        </form>
    </div>
    <script>


    </script>

</body>

</html>