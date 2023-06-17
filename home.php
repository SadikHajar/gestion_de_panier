<?php

session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
    $pdo = new PDO('mysql:host=localhost; dbname=test_db', 'root', '');
    $stmt = $pdo->prepare("SELECT *  FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

?>


<!DOCTYPE html>
<html lang="en">
<head><link rel="stylesheet" href="produit_style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
<section class='trending-product' id='trending'>
<div class='center-text'>
<h2>Bienvenue <span><?php echo $user["name"]; ?></span></h2>
    </div>

    <a href="product.php">Retour aux achats</a><br><br>
    <a href="logout.php">Déconnexion</a>
</section>
</body>
</html>