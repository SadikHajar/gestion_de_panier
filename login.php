<?php

session_start();

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $pdo = new PDO('mysql:host=localhost; dbname=test_db', 'root', '');

    $stmt = $pdo->prepare("SELECT *  FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION['user_id'] = $user['id'];

        header('Location: product.php');
        exit();
    } else {
        echo "<script> alert('Invalid email or password'); </script>";
    }
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
        <h1>Connexion</h1>
        <form method="post">
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
            <div class="pass">Mot de passe oublié?</div>
            <input type="submit" name="submit" value="Se connecter">
            <div class="signup_link">
                Créer un compte? <a href="register.php">s'inscrire</a>
            </div>
        </form>
    </div>
</body>

</html>