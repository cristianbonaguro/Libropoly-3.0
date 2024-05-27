<?php
session_start();

if(!isset($_SESSION['username']))
        header("Location: ../login.php");

$conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ? google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Amatic+SC&family=Nova+Square&family=Roboto+Mono:wght@600&display=swap"
    rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <!--? header -->
    <link rel="stylesheet" href="index.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libropoly | Search</title>
</head>
<body>
<div class="hero-cont">
    <div class="hero">
        <nav>
            <ul>
                <li><a href="../login.php" >LOGOUT</a></li>
                <li class="margin"><a href="index.php">HOME</a></li>
                <li class="margin"><a href="#1">GENERI</a></li> 
                <li class="margin"><a href="carrello.php">CARRELLO</a></li>
            </ul>
        </nav>
        <div class="title">
            <h1>LIBROPOLY</h1>
        </div>
    </div>
</div>
<div class="form">
<form method="POST">
    <input type="text" name="ricerca" placeholder="Ricerca">
    <input type="submit" name="submit" value="Cerca">
</form>
</div>
<div class="contenitore">
    <?php
        if (isset($_POST['submit'])) {
            $prod = $conn -> query(<<<SQL
            SELECT * 
            FROM  LIBRI_INNODB
            WHERE LOWER(LIBRI_INNODB.Titolo) 
            LIKE "%$_POST[ricerca]%"; 
            SQL);
            $ricerca = $_POST['ricerca'];
        if($prod -> num_rows > 0)
        {
            foreach ($prod as $row) {
                echo <<<HTML
                        <div id="$row[Id]" class="card">
                            <div class="border">
                            <div class="up">
                                <div class="nome $row[Classe]">
                                    <h2>$row[Genere]</h2>
                                <h1>$row[Titolo]</h1>
                                </div>
                            </div>
                            <div class="down">
                                <div class="voci">
                                <div class="voce">
                                    <div class="info">
                                        <h2>Autore</h2>
                                    </div>
                                    <div class="risp">
                                        <h2>$row[Autore]</h2>
                                    </div>
                                </div>
                                <div class="voce">
                                    <div class="info">
                                        <h2>Anteprima</h2>
                                    </div>
                                    <div class="risp">
                                        <h2>$row[Anteprima]</h2>
                                    </div>
                                </div>
                                <div class="voce">
                                    <div class="info">
                                        <h2>Editore</h2>
                                    </div>
                                    <div class="risp">
                                        <h2>$row[Editore]</h2>
                                    </div>
                                </div>
                                <div class="voce">
                                    <div class="info">
                                        <h2>Pagine</h2>
                                    </div>
                                    <div class="risp">
                                        <h2>$row[NPagine]</h2>
                                    </div>
                                </div>
                                <div  class="voce">
                                    <div class="info">
                                        <h2>Costo</h2>
                                    </div>
                                    <div class="risp">
                                        <h2>€$row[Prezzo]</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="bottoni">
                                <div class="btn">
                                    <button id=$row[LinkAnteprima] class ="$row[Classe] bookbtn">Leggi anteprima</button>
                                </div>
                                <div class="btn">
                                    <button class="$row[Classe]"><a href="index.php?action=addg&nome=$row[Id]">Aggiungi carrello</a></button>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    HTML;
            }
        }
        }
    ?>
</div>
</body>
</html> 