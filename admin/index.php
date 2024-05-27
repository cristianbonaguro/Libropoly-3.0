<?php
session_start();

if(!isset($_SESSION['username']))
        header("Location: ../login.php");

$conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if(isset($_GET['action'])){
    switch ($_GET['action']) {
        case 'rem':
            $conn -> query(<<<SQL
                            DELETE 
                            FROM `LIBRI_INNODB` 
                            WHERE `LIBRI_INNODB`.`Id` = $_GET[nome];
                            SQL);
            break;
    }
}


$libri = array();
$gialli = array();
$fantasy = array();
$fantascienza = array();
$horror = array();
$formazione = array();
$classici = array();
$storici = array();
$bestseller = array();




$libri['gialli']=['name'=>'Gialli','image'=>'../images/gialli.png' ,'p1'=>'Detective','p2'=>'Omicidio','p3'=>'Suspense','voce'=>'Vai a Gialli','id'=>'#4'];
$libri['fantascienza']=['name'=>'Fantascienza','image'=>'../images/fantascientifico.png' ,'p1'=>'Robot','p2'=>'Futuro','p3'=>'Tecnologia','voce'=>'Vai a Fantascienza','id'=>'#9'];
$libri['fantasy']=['name'=>'Fantasy','image'=>'../images/fantasy.png' ,'p1'=>'Immaginazione','p2'=>'Avventura','p3'=>'Mondi fantstici','voce'=>'Vai a Fantasy','id'=>'#17'];
$libri['horror']=['name'=>'Horror','image'=>'../images/horror.png' ,'p1'=>'Paura','p2'=>'Terrore','p3'=>'Mostri','voce'=>'Vai a Horror','id'=>'#25'];
$libri['formazione']=['name'=>'R.D.Formazione','image'=>'../images/formazione.png' ,'p1'=>'Cresce','p2'=>'Impara','p3'=>'Cambia','voce'=>'Romanzi Di Formazione','id'=>'#33'];
$libri['classici']=['name'=>'Classici','image'=>'../images/classici.png' ,'p1'=>'Eternità','p2'=>'Umanità','p3'=>'Ispirazione','voce'=>'Vai a Classici','id'=>'#41'];
$libri['storici']=['name'=>'Storici','image'=>'../images/storico.png' ,'p1'=>'Passato','p2'=>'Personaggi','p3'=>'Eventi','voce'=>'Vai a Storici','id'=>'#49'];
$libri['best seller']=['name'=>'Best Seller','image'=>'../images/bestseller.png' ,'p1'=>'Popolari','p2'=>'Divertenti','p3'=>'Coinvolgenti','voce'=>'Vai a Best Seller','id'=>'#57'];

if(isset($_GET['action'])){
    $id = $_GET['nome'];
    $crtprod = $conn -> query(<<<SQL
                                SELECT * 
                                FROM CARRELLO 
                                WHERE CARRELLO.Codprod = $id
                                AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                        FROM ACCOUNT 
                                                        Where ACCOUNT.Username = "$_SESSION[username]") 
                                                        AND CARRELLO.Complatato = 0; 
                            SQL);
    $utente = $conn -> query(<<<SQL
                            SELECT * 
                            FROM ACCOUNT 
                            Where ACCOUNT.Username = "$_SESSION[username]"; 
                        SQL);
    if($crtprod -> num_rows == 0){
        foreach ($utente as $row) {
            $conn -> query(<<<SQL
                            INSERT 
                            INTO `CARRELLO` (`Id`, `CodUtente`, `Codprod`, `Quantita`, `Complatato`) 
                            VALUES (NULL, '$row[Id]', '$id', '1', '0'); 
                            SQL);
        }
    }
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LIBROPOLY | ADMIN | HOME</title>
</head>
<body>
    <div class="hero-cont">
    <div class="hero">
        <nav>
            <ul>
                <li><a href="../login.php" >LOGOUT</a></li>
                <li class="margin"><a href="#1">GENERI</a></li>
                <li class="margin"><a href="nuovo.php">NUOVO</a></li>
                
            </ul>
        </nav>
        <div class="title">
            <h1>LIBROPOLY</h1>
        </div>
    </div>
</div>


<!-- ? generi -->
<div id="1" class="contenitore">
<?php

foreach($libri as $generi) { 

    $libri = <<<HTML
        <div class="card">
        <div class="border">
        <div class="up_g">
            <div class="nome generi">
            <img src= $generi[image] alt="image">
            </div>
        </div>
        <div class="down_g">
        <div class="text">
            <div class="gen">
            <h2>$generi[name]</h2>
        </div>
            <div class="description">
            <div class="keyword">
                <h1>Parole chiave</h1>
            </div>
            <div class="key">
            <h3>$generi[p1]</h3>
            </div>
            <div class="key">
            <h3>$generi[p2]</h3>
            </div>
            <div class="key">
            <h3>$generi[p3]</h3>
        </div>
        </div>
        </div>
        <div class="bottonig">
            <div class="btn">
                <button class="generibnt"><a href=$generi[id]>$generi[voce]</a></button>
            </div>
        </div>
        </div>
        </div>
    </div>
    HTML;
    echo $libri;
}
?>
</div>



<div  class="contenitore">
    <?php
    $conn=new mysqli('localhost','root','','my_cristian3g');

    if($conn->connect_error) {
        print("<h1>connessione fallita</h1>");
        exit;
    }

    $sql="SELECT * FROM LIBRI_INNODB ORDER BY LIBRI_INNODB.Genere; ";

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $libri = <<<HTML
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
            <!-- <div class="btn">
            <button class="$row[Classe]"><a href="index.php?action=add&nome=$row[Id]">Aggiungi elemento</a></button>
            </div> -->
            <div class="btn">
                <button class="$row[Classe]"><a href="index.php?action=rem&nome=$row[Id]">Elimina elemento</a></button>
            </div>
        </div>
          </div>
        </div>
      </div>
HTML;
echo $libri;


        }
    }
?>
</div>

 






    <!-- ? btn scroll -->
    <button id="btnscroll" onclick="topFunction()"><img src="../images/upload.png" alt="arrow"></button>
  </body>
<script src="script.js"></script>
</html>
