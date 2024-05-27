<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'my_cristian3g');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if(isset($_POST['submit'])){
    if ($_POST['username']=='admin'&&$_POST['password']=='admin') {
        $_SESSION['username'] = $_POST['username'];
            header('Location: admin/index.php');
    }
    else{
    $utente = $conn -> query(<<<SQL
                        SELECT * 
                        FROM `ACCOUNT` 
                        WHERE ACCOUNT.Username = "$_POST[username]"
                        AND ACCOUNT.Password = "$_POST[password]";
                    SQL);
    if ($utente -> num_rows > 0) {
            $_SESSION['username'] = $_POST['username'];
            header('Location: cliente/index.php');
    }
    else{
        $errore = "credenziali sbagliate";
    }
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Libropoly | Login</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form">
            <form method="post">
            <h2>Login</h2>
            <?php if(isset($errore)) { echo "<p style='color:#e41414;'>$errore</p>"; } ?> 
            <input type="text" placeholder="username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit" name="submit">Login</button>
            <p class="link">Do not have an account? <a href="sing_up.php">Sing up here</a></p>
        </div>
        </form>
    </div>
</body>
</html>