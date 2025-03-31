<?php
session_start();
$usersFile = 'users.txt';
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (file_exists($usersFile) && ($file = fopen($usersFile, "r"))) {
        while (($line = fgets($file)) != false) {
            list($storedUser, $storedPassword) = explode('|', trim($line));
            if ($storedUser == $username && password_verify($password, $storedPassword)) {
                $_SESSION['username'] = $username;
                fclose($file);
                header("Location: index.html");
                exit;
            }
        }
        fclose($file);
    }
    echo "<p>Credenziali errate.</p>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleLogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400&display=swap" rel="stylesheet">

</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Nome utente" required>
            <input type="password" name="password" placeholder="Password" required> <br>
            <button type="submit" name="login">Accedi</button>
        </form>
        <p>Non hai un account? <a href="registrazione.php">Registrati</a></p>
    </div>

</body>

</html>