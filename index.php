<?php
session_start();

if (!isset($_SESSION["messaggio_stampato"])) {
    $_SESSION["messaggio_stampato"] = true;
}

if (isset($_GET["reset"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$importoPagare;
$netto;

function regionCheck($reg)
{
    switch ($reg) {
        case "Grifondoro":
            return -1;
        case "Serpeverde":
            return 0;
        case "Corvonero":
            return 1;
        case "Tassorosso":
            return 2;
        default:
            return 100;
    }
}

function mensCheck($mens)
{
    switch ($mens) {
        case 12:
            return 0;
        case 13:
            return -1;
        case 14:
            return -2;
        default:
            return 100;
    }
}

function calcolaTax($scV, $regione, $mensilita, $figli)
{
    $modificatoreRegione = regionCheck($regione);
    $modificatoreMensilita = mensCheck($mensilita);

    $taxV = $scV - $modificatoreRegione - $modificatoreMensilita - $figli;

    return max($taxV, 0); // Impedisce alla percentuale di essere inferiore a 0
}

function redTax($reddito, $regione, $mensilita, $figli)
{
    $sc1 = calcolaTax(6, $regione, $mensilita, $figli) / 100;
    $sc2 = calcolaTax(12, $regione, $mensilita, $figli) / 100;
    $sc3 = calcolaTax(18, $regione, $mensilita, $figli) / 100;
    $sc4 = calcolaTax(24, $regione, $mensilita, $figli) / 100;
    $sc5 = calcolaTax(30, $regione, $mensilita, $figli) / 100;
    $sc6 = calcolaTax(36, $regione, $mensilita, $figli) / 100;
    $sc7 = calcolaTax(42, $regione, $mensilita, $figli) / 100;

    if ($reddito <= 15000) {
        return $reddito * $sc1;
    } elseif ($reddito <= 30000) {
        return (15000 * $sc1) + (($reddito - 15000) * $sc2);
    } elseif ($reddito <= 45000) {
        return (15000 * $sc1) + (15000 * $sc2) + (($reddito - 30000) * $sc3);
    } elseif ($reddito <= 60000) {
        return (15000 * $sc1) + (15000 * $sc2) + (15000 * $sc3) + (($reddito - 45000) * $sc4);
    } elseif ($reddito <= 75000) {
        return (15000 * $sc1) + (15000 * $sc2) + (15000 * $sc3) + (15000 * $sc4) + (($reddito - 60000) * $sc5);
    } elseif ($reddito <= 90000) {
        return (15000 * $sc1) + (15000 * $sc2) + (15000 * $sc3) + (15000 * $sc4) + (15000 * $sc5) + (($reddito - 75000) * $sc6);
    } else {
        return (15000 * $sc1) + (15000 * $sc2) + (15000 * $sc3) + (15000 * $sc4) + (15000 * $sc5) + (15000 * $sc6) + (($reddito - 90000) * $sc7);
    }
}

if (isset($_POST['index'])) {
    $reddito = $_POST['reddito'];
    $mensilita = $_POST['mensilita'];
    $regione = $_POST['regione'];
    $figli = $_POST['figli'];

    $importoPagare = redTax($reddito, $regione, $mensilita, $figli);
    $netto = $reddito - redTax($reddito, $regione, $mensilita, $figli);
    echo "<p>I valori sono: importo da pagare: $importoPagare e netto: $netto</p>";
    $tasseFile = 'tasse.txt';
    // Scrittura nel file
    if ($file = fopen($tasseFile, "a")) { #sovrascrive senza cancellare
        fwrite($file, "$importoPagare|$netto\n");
        fclose($file);
        echo "<p>caricamento completato.</p>";
    } else {
        echo "<p>Errore: Impossibile accedere al file calcolo tasse.</p>";
    }
}





?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@400&display=swap" rel="stylesheet">
    <title>Calcolatore Tasse</title>
</head>
<!--------------------------------------------------------------------->

<body>
    <!-- Navbar
    <div class="navbar">
        <div class="logo">
            <img src="Assets/img/logo.png" alt="Logo">
        </div>
        <div class="auth-buttons">
            <a href="login.php">
                <button type="submit" name="login">Login</button>
            </a>
        </div>

    </div>
     -->
    <button class="icon" id="btnLogin">

        </svg>

    </button>
    <div class="container">
        <div class="content">
        </div>
    </div>
    <div class="menu">
        <div class="wrapper">
            <h1 id="titolo">Calcolatore Tasse</h1>
            <div id="userInput">
                <form method="post" action="index.php" class="userInput" name="index">
                    <label for="RAL">Reddito Annuo Lordo (€):</label><br>
                    <input type="number" name="reddito" id="RAL" min="0" required><br><br>

                    <legend>Seleziona il numero di mensilità:</legend>

                    <input type="radio" name="mensilita" id="men12" value="12" required>
                    <label for="men12">12</label><br>

                    <input type="radio" name="mensilita" id="men13" value="13">
                    <label for="men13">13</label><br>

                    <input type="radio" name="mensilita" id="men14" value="14">
                    <label for="men14">14</label><br><br>

                    <legend>Seleziona la regione:</legend>

                    <input type="radio" name="regione" id="regGrif" value="Grifondoro" required>
                    <label for="regGrif">Grifondoro</label><br>

                    <input type="radio" name="regione" id="regSer" value="Serpeverde">
                    <label for="regSer">Serpeverde</label><br>

                    <input type="radio" name="regione" id="regCor" value="Corvonero">
                    <label for="regCor">Corvonero</label><br>

                    <input type="radio" name="regione" id="regTas" value="Tassorosso">
                    <label for="regTas">Tassorosso</label><br><br>

                    <label for="inputFigli">Inserire num. figli: </label><br>
                    <input type="number" name="figli" id="inputFigli" min="0" required><br><br>

                    <input type="submit" value="Calcola" id="submit" name="index">
                    <a href="?reset=true">Reset</a>

                </form>

                <div id="risultati">
                    <h3>Risultati:</h3>
                    <p id="risultati1">
                        <?php
                        if (isset($_POST["index"])) {
                            echo "da pagare= $importoPagare";
                        }
                        ?>
                    </p>
                    <!----CAMPO IMPORTO DA PAGARE---->
                    <p id="risultati2">
                        <?php
                        if (isset($_POST["index"])) {
                            echo "soldi rimanenti= $netto";
                        }
                        ?>
                    </p> <!----CAMPO SOLDI RIMANENTI (NETTO)---->
                </div>
            </div>
        </div>
    </div>
    </div>


</body>

</html>