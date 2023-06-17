<?php

session_start();

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $pdo = new PDO('mysql:host=localhost; dbname=test_db', 'root', '');

    $stmt = $pdo->prepare("INSERT INTO user (name, phone, email, password) VALUES(?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $email, password_hash($password, PASSWORD_DEFAULT)]);
    header("Location: login.php");
    exit();

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Website</title>
</head>

<body>
    <div class="center">
        <h1>Inscription</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" name="name" required>
                <span></span>
                <label>Nom</label>
            </div>
            <div class="txt_field">
                <input type="text" name="phone" required>
                <span></span>
                <label>Telephone</label>
            </div>
            <div class="txt_field">
                <input type="text" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required>
                <span></span>
                <label>Mot de passe</label>
            </div>
            <input type="submit" name="submit" value="Inscrire">
            <div class="signup_link">
                Vous avez déjà un compte? <a href="login.php">se connecter</a>
            </div>
        </form>
    </div>
</body>

</html>