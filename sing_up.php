<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if(isset($_POST['submit'])){
    $esistentemail = $conn -> query(<<<SQL
                                    SELECT * 
                                    FROM `ACCOUNT` 
                                    WHERE ACCOUNT.Email = "$_POST[email]"; 
                                SQL);
    if (!$esistentemail -> num_rows > 0) {
        $esistenteuser = $conn -> query(<<<SQL
                                    SELECT * 
                                    FROM `ACCOUNT` 
                                    WHERE ACCOUNT.Username = "$_POST[username]"; 
                                SQL);
            if (!$esistenteuser -> num_rows > 0){
            if ($_POST['password']==$_POST['confirm']) {
                $conn -> query(<<<SQL
                                INSERT 
                                INTO `ACCOUNT` (`Id`, `Username`, `Email`, `Password`) 
                                VALUES (NULL, '$_POST[username]', '$_POST[email]', '$_POST[password]'); 
                            SQL);
            $_SESSION['username'] = $_POST['username'];
            header('Location: cliente/index.php');
            } 
            else {
                $error = "Le password non corrispondono";
            }
        }
        else {
            $error = "Username già in uso";
        }
    }
    else{
        $error = 'Email già in uso';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Libropoly | Sing up</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form">
            <form method="post">
            <h2>Sing up</h2>
            <?php if(isset($error)) { echo "<p style='color:#e41414;'>$error</p>"; } ?>
            <input type="text" placeholder="username" name="username" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <input type="password" placeholder="Confirm Password" name="confirm" required>
            <button type="submit" name="submit">Sing up</button>
            <p class="link">Back to Login? <a href="login.php">Click here</a></p>
        </div>
        </form>
    </div>
</body>
</html>