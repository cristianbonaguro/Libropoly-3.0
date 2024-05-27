<?php
define("UPLOAD_DIR", "../images/");
session_start();

if(!isset($_SESSION['username']))
        header("Location: ../login.php");

$conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {

    $file = $_FILES['image'];
        $fileName = basename($file['name']);
        $uploadFilePath = UPLOAD_DIR . $fileName;

        if (file_exists($uploadFilePath)) {
            echo "Error: File already exists.";
        } else {
            if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                $link = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
                $url = $link . "/Libropoly3.0/images/". $fileName;
                 switch($_POST['genere']){
        case 'storico':
            $genere = "storici";
            break;
            case 'best':
                $genere = "best sellers";
                break;
            default:
            $genere = $_POST['genere'];
            break;
    }
                $conn -> query(<<<SQL
                        INSERT 
                        INTO `LIBRI_INNODB` (`Id`, `Titolo`, `Autore`, `Anteprima`, `Editore`, `NPagine`, `Prezzo`, `LinkAnteprima`, `Genere`, `Image`, `Classe`) 
                        VALUES (NULL, '$_POST[nome]', '$_POST[autore]', 'anteprima', '$_POST[editore]', '$_POST[pagine]', '$_POST[prezzo]', 'https://books.google.it/books?id=CLKOCgAAQBAJ&newbks=0&lpg=PA1&hl=it&pg=PA1&output=embed', '$genere', '$url', '$_POST[genere]'); 
                    SQL);}
                else {
                    echo "No file uploaded.";
                }
            }
        
    
    $anteprima=strval($_POST['anteprima']); 

    switch($_POST['genere']){
        case 'storico':
            $genere = "storici";
            break;
            case 'best':
                $genere = "best sellers";
                break;
            default:
            $genere = $_POST['genere'];
            break;
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
    <link rel="stylesheet" href="add.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LIBROPOLY | ADMIN | ADD</title>
</head>
<body>
    <div class="hero-cont">
    <div class="hero">
        <nav>
            <ul>
                <li><a href="../login.php" >LOGOUT</a></li>
                <li class="margin"><a href="#1">GENERI</a></li>
                <li class="margin"><a href="index.php">HOME</a></li>
            </ul>
        </nav>
        <div class="title">
            <h1>LIBROPOLY</h1>
        </div>
    </div>
</div>


<!-- ? generi -->
<div id="1" class="contenitore">
    <form method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="border">
            <div class="up">
                <div id="gen" class="nome">
                    <select id="genere" name="genere" required>
                        <option value="" selected disabled hidden>Genere</option>
                        <option value="giallo">Giallo</option>
                        <option value="fantascienza">Fantascienza</option>
                        <option value="fantasy">Fantasy</option>
                        <option value="horror">Horror</option>
                        <option value="formazione">Formazione</option>
                        <option value="classici">Classici</option>
                        <option value="storico">Storico</option>
                        <option value="best">Best Sellers</option>
                    </select>
                <h1 ><input id="gen2" type="text" name="nome"placeholder="titolo"required></h1>
                </div>
            </div>
            <div class="down">
                <div class="voci">
                <div class="voce">
                    <div class="info">
                        <h2>Autore</h2>
                    </div>
                    <div class="risp">
                        <h2><input type="text"name="autore"placeholder="autore"required></h2>
                    </div>
                </div>
                <div class="voce">
                    <div class="info">
                        <h2>Anteprima</h2>
                    </div>
                    <div class="risp">
                        <h2><input type="date"name="anteprima"placeholder="anteprima"required></h2>
                    </div>
                </div>
                <div class="voce">
                    <div class="info">
                        <h2>Editore</h2>
                    </div>
                    <div class="risp">
                        <h2><input type="text"name="editore"placeholder="editore"required></h2>
                    </div>
                </div>
                <div class="voce">
                    <div class="info">
                        <h2>Pagine</h2>
                    </div>
                    <div class="risp">
                        <h2><input type="number"name="pagine"placeholder="n.pagine"required></h2>
                    </div>
                </div>
                <div  class="voce">
                    <div class="info">
                        <h2>Costo</h2>
                    </div>
                    <div class="risp">
                        <h2><input type="number"name="prezzo"placeholder="prezzo"required></h2>
                    </div>
                </div>
            </div>
            <div class="bottoni">
                <div class="btn">
                <button id="gen3"><input type="file"name="image"required></button>
                </div>
                <div class="btn">
                    <button  id="gen4" type="submit" name="submit">Aggiungi prodotto</button>
                </div>
            </div>
              </div>
            </div>
    </form>
</div>




</div>

 
  </body>
<script src="../script.js"></script>
<script>
    
    document.getElementById('genere').addEventListener('change', function() {


        document.getElementById('gen').classList.remove('giallo');
        document.getElementById('gen2').classList.remove('giallo');
        document.getElementById('gen3').classList.remove('giallo');
        document.getElementById('gen4').classList.remove('giallo');

        document.getElementById('gen').classList.remove('fantascienza');
        document.getElementById('gen2').classList.remove('fantascienza');
        document.getElementById('gen3').classList.remove('fantascienza');
        document.getElementById('gen4').classList.remove('fantascienza');

        document.getElementById('gen').classList.remove('fantasy');
        document.getElementById('gen2').classList.remove('fantasy');
        document.getElementById('gen3').classList.remove('fantasy');
        document.getElementById('gen4').classList.remove('fantasy');

        document.getElementById('gen').classList.remove('horror');
        document.getElementById('gen2').classList.remove('horror');
        document.getElementById('gen3').classList.remove('horror');
        document.getElementById('gen4').classList.remove('horror');

        document.getElementById('gen').classList.remove('formazione');
        document.getElementById('gen2').classList.remove('formazione');
        document.getElementById('gen3').classList.remove('formazione');
        document.getElementById('gen4').classList.remove('formazione');

        document.getElementById('gen').classList.remove('classici');
        document.getElementById('gen2').classList.remove('classici');
        document.getElementById('gen3').classList.remove('classici');
        document.getElementById('gen4').classList.remove('classici');

        document.getElementById('gen').classList.remove('storico');
        document.getElementById('gen2').classList.remove('storico');
        document.getElementById('gen3').classList.remove('storico');
        document.getElementById('gen4').classList.remove('storico');

        document.getElementById('gen').classList.remove('best');
        document.getElementById('gen2').classList.remove('best');
        document.getElementById('gen3').classList.remove('best');
        document.getElementById('gen4').classList.remove('best');

        document.getElementById('gen').classList.add(this.value);
        document.getElementById('gen2').classList.add(this.value);
        document.getElementById('gen3').classList.add(this.value);
        document.getElementById('gen4').classList.add(this.value);


    })
</script>
</html>
