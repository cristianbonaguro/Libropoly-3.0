<?php
    session_start();

    if(!isset($_SESSION['username']))
        header("Location: ../login.php");

        $conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        $Carrello = $conn -> query(<<<SQL
                                SELECT * 
                                FROM CARRELLO 
                                INNER JOIN LIBRI_INNODB 
                                ON LIBRI_INNODB.Id = CARRELLO.Codprod 
                                WHERE CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                FROM ACCOUNT 
                                Where ACCOUNT.Username = "$_SESSION[username]") 
                                AND CARRELLO.Complatato = 0; 
                            SQL);
        if($Carrello -> num_rows > 0){
            $_SESSION['spesa'] = 0;
            foreach ($Carrello as $row ) {
                $_SESSION['spesa'] += $row['Prezzo']*$row[Quantita];
            }
            
        }

        if(isset($_GET['action'])){
                $prod = $conn -> query(<<<SQL
                                SELECT * 
                                FROM CARRELLO 
                                INNER JOIN LIBRI_INNODB 
                                ON LIBRI_INNODB.Id = CARRELLO.Codprod 
                                WHERE CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                            FROM ACCOUNT 
                                                            Where ACCOUNT.Username = "$_SESSION[username]") 
                                                            AND LIBRI_INNODB.Id = $_GET[id] 
                                                            AND CARRELLO.Complatato = 0; 
                            SQL);
            switch ($_GET['action']) {
                case 'svuota':{
                    $conn -> query(<<<SQL
                                        DELETE FROM CARRELLO 
                                        WHERE CARRELLO.Complatato = 0 
                                        AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]"); 
                                    SQL);
                                    unset($_SESSION['spesa']);
                }
                    break;
                case 'add':
                    {
                    foreach ($prod as $row){
                            $nuovaquantita = intval($row['Quantita']) + 1;
                            $conn -> query(<<<SQL
                                            UPDATE CARRELLO 
                                            SET Quantita = $nuovaquantita 
                                            WHERE CARRELLO.Codprod = $row[Codprod] 
                                            AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]");  
                                            SQL);
                    }
                }
                break;
                case 'meno':
                    {
                    foreach ($prod as $row){
                        if($row['Quantita']>1){
                            $nuovaquantita = intval($row['Quantita']) - 1;
                            $conn -> query(<<<SQL
                                            UPDATE CARRELLO 
                                            SET Quantita = $nuovaquantita 
                                            WHERE CARRELLO.Codprod = $row[Codprod] 
                                            AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]");  
                                            SQL);
                        }
                        else{
                            $conn -> query(<<<SQL
                                            DELETE 
                                            FROM CARRELLO 
                                            WHERE CARRELLO.Codprod = $row[Codprod]   
                                            AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]");  
                                            SQL);
                        }
                    }
                }
                break;
                case 'rimuovi':
                    {
                    foreach ($prod as $row){
                        $conn -> query(<<<SQL
                                        DELETE 
                                        FROM CARRELLO 
                                        WHERE CARRELLO.Codprod = $row[Codprod]   
                                        AND CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]");  
                        SQL);
                        $_SESSION[spesa] = 0;
                    }
                }
                break;
                case 'completa':
                    {
                    
                        $conn -> query(<<<SQL
                                            UPDATE CARRELLO 
                                            SET CARRELLO.Complatato = 1 
                                            WHERE CARRELLO.CodUtente = (SELECT ACCOUNT.Id 
                                                                FROM ACCOUNT 
                                                                Where ACCOUNT.Username = "$_SESSION[username]");  
                                            SQL);
                $_SESSION[spesa] = 0;
                }
                break;
            }
            if($_SESSION['spesa']<1)
            unset($_SESSION['spesa']);
            header('Location: carrello.php');
            exit();
            
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
    <link rel="stylesheet" href="carrello.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libropoly | Carrello</title>
</head>
<body>
<div class="hero-cont">
    <div class="hero">
                <h2>il tuo Carrello</h1>
        <div class="title">
            <h1>LIBROPOLY</h1>
        </div>
    </div>
</div>

<div class="contenitore">
    <div class="cart">
        <div class="tot red">
        <div class="prezzo">
        <h1> <?php if(isset($_SESSION['spesa'])&&$_SESSION['spesa']>0) echo "TOTALE : ".$_SESSION['spesa']." €"; else echo "IL CARRELLO E' VUOTO";?></h1>
        </div>
        <div class="btns">
            <div class="btn">
        <button><a href="index.php">CONTINUA ACQUISTI</a></button>
        </div>
        <div class="btn">
        <button><a href="carrello.php?action=svuota">SVUOTA IL CARRELLO</a></button>
        </div>
        <div class="btn">
        <button><a href="carrello.php?action=completa">COMPLETA ACQUISTO</a></button>
        </div>
        </div>
    </div>
    <?php
if($Carrello -> num_rows > 0){

foreach($Carrello as $row)
{

    $costo = $row['Quantita']*$row['Prezzo'];

    $cart = <<<HTML
        
    <div class="tot cont">
        <div class="img cont">
            <img src="$row[Image]" alt="book">
        </div>
        <div class="other cont">
        <div class="nome">
                <h2 class="$row[Genere]"> $row[Titolo]</h2>
                <h1>$row[Autore]</h1>
        </div>
        <div class="qty cont">
            <div class="elem">
        <button><a href="carrello.php?action=meno&id=$row[Codprod]"><img src="../images/remove.png" alt="meno"></a></button>  
            </div>
            <div class="elem">
            <h1>$row[Quantita]</h1>  
            </div>
            <div class="elem cont"> 
            <button><a href="carrello.php?action=add&id=$row[Codprod]"><img src="../images/add.png" alt="più"></a></button>
            </div>   
        </div>
        <div class="val cont">

            <div class="fifty cont $row[Genere]">
            <button ><a href="carrello.php?action=rimuovi&id=$row[Codprod]">Rimuovi</a></button>
            </div>
            <div class="fifty cont">
            <h1>$costo €</h1>
        </div>
        </div>


        </div>
    </div>
    HTML;
    echo $cart;
}
}
    ?>
</div>
</div>
</body>
</html>