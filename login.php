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
                header("Location: index.php");
                exit;
            }else{
                $_SESSION["messaggio"] = "Credenziali errate";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
        fclose($file);
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styleLogin.css">
  

</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION['messaggio'])){
            echo"<p> ".$_SESSION['messaggio']."</p>";
            unset($_SESSION["messaggio"]);
        }
        ?>
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