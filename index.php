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


    if (!empty($_SESSION['abbonamento'])) {
        $todsFile = "tods_" . $_SESSION["username"] . ".txt";

        $dati = "$reddito|$mensilita|$regione|$figli|$importoPagare|$netto\n";
        file_put_contents($todsFile, $dati, FILE_APPEND | LOCK_EX);
        
    }
    if (isset($_POST['delete'])) {
        $todos = file($todosFile, FILE_IGNORE_NEW_LINES);
        unset($todos[$_POST['delete']]); #tolto l'elemento da eliminare, adesso todos ha un elemento in meno
        #rimetto tutti i todos, da cui ne è stato tolto uno, nel todosFile
        file_put_contents($todosFile, implode("\n", $todos) . "\n");
    }
    
        
    }



    /*
       $tasseFile = 'tasse.txt';
       // Scrittura nel file
       if ($file = fopen($tasseFile, "a")) { #sovrascrive senza cancellare
           fwrite($file, "$importoPagare|$netto\n");
           fclose($file);
           echo "<p>caricamento completato.</p>";
       } else {
           echo "<p>Errore: Impossibile accedere al file calcolo tasse.</p>";
       }*/



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

    <!--<button class="icon" id="btnLogin">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
            <path
                d="M480-492.31q-57.75 0-98.87-41.12Q340-574.56 340-632.31q0-57.75 41.13-98.87 41.12-41.13 98.87-41.13 57.75 0 98.87 41.13Q620-690.06 620-632.31q0 57.75-41.13 98.88-41.12 41.12-98.87 41.12ZM180-187.69v-88.93q0-29.38 15.96-54.42 15.96-25.04 42.66-38.5 59.3-29.07 119.65-43.61 60.35-14.54 121.73-14.54t121.73 14.54q60.35 14.54 119.65 43.61 26.7 13.46 42.66 38.5Q780-306 780-276.62v88.93H180Zm60-60h480v-28.93q0-12.15-7.04-22.5-7.04-10.34-19.11-16.88-51.7-25.46-105.42-38.58Q534.7-367.69 480-367.69q-54.7 0-108.43 13.11-53.72 13.12-105.42 38.58-12.07 6.54-19.11 16.88-7.04 10.35-7.04 22.5v28.93Zm240-304.62q33 0 56.5-23.5t23.5-56.5q0-33-23.5-56.5t-56.5-23.5q-33 0-56.5 23.5t-23.5 56.5q0 33 23.5 56.5t56.5 23.5Zm0-80Zm0 384.62Z" />
</svg>
        ☰
    </button>-->
    <button id="menuButton" class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
            <path
                d="M480-492.31q-57.75 0-98.87-41.12Q340-574.56 340-632.31q0-57.75 41.13-98.87 41.12-41.13 98.87-41.13 57.75 0 98.87 41.13Q620-690.06 620-632.31q0 57.75-41.13 98.88-41.12 41.12-98.87 41.12ZM180-187.69v-88.93q0-29.38 15.96-54.42 15.96-25.04 42.66-38.5 59.3-29.07 119.65-43.61 60.35-14.54 121.73-14.54t121.73 14.54q60.35 14.54 119.65 43.61 26.7 13.46 42.66 38.5Q780-306 780-276.62v88.93H180Zm60-60h480v-28.93q0-12.15-7.04-22.5-7.04-10.34-19.11-16.88-51.7-25.46-105.42-38.58Q534.7-367.69 480-367.69q-54.7 0-108.43 13.11-53.72 13.12-105.42 38.58-12.07 6.54-19.11 16.88-7.04 10.35-7.04 22.5v28.93Zm240-304.62q33 0 56.5-23.5t23.5-56.5q0-33-23.5-56.5t-56.5-23.5q-33 0-56.5 23.5t-23.5 56.5q0 33 23.5 56.5t56.5 23.5Zm0-80Zm0 384.62Z" />
        </svg>
    </button> <!-- Pulsante per aprire il menu -->

    <div id="dropdownMenu" class="hidden">
        <a href="profilo.php">Profilo</a>
        <a href="login.php">Logout</a>
    </div>

    <div id="risultati" >
        <div style="display: <?php echo isset($_POST["index"]) ? 'block' : 'none'; ?>;">
            <h3>Risultati:</h3>
            <p id="risultati1"><!----CAMPO IMPORTO DA PAGARE---->
                <?php
                if (isset($_POST["index"])) {
                    echo "da pagare= $importoPagare";
                }
                ?>
            </p>

            <p id="risultati2"><!----CAMPO SOLDI RIMANENTI (NETTO)---->
                <?php
                if (isset($_POST["index"])) {
                    echo "soldi rimanenti= $netto";
                }
                ?>
            </p>
        </div>
        
    </div>

    <div class="wrapper">
        <h1 id="titolo">Calcolatore Tasse</h1>
        <form method="post" action="index.php" name="index">
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

            
            <button id="calc-btn" type="submit" class="btn">
                Calcola
            </button>
            <button id="reset-btn" class="btn">
                Reset
            </button>

        </form>

    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const version = '1.2.1'

            console.log('@VERSION: ', version)

            const menuButton = document.getElementById("menuButton")
            const resetButton = document.getElementById("reset-btn")
            const dropdownMenu = document.getElementById("dropdownMenu")

            menuButton.addEventListener("click", function () {
                dropdownMenu.style.display = 'flex'
            })

            resetButton.addEventListener('click', (e) => {
                window.location.href = '?reset=true'
            })  

            // Chiudi il menu se si clicca fuori
            document.addEventListener("click", function (event) {
                if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none'
                }
            })

        })

    </script>

</body>

</html>