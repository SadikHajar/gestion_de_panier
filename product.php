<?php
session_start();


// Connect to MySQL database
$pdo = new PDO('mysql:host=localhost; dbname=test_db', 'root', '');

// Retrieve all products from the database
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();

// Check if user has added an item to their cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if item is already in the cart
    $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        // Update quantity of existing cart item
        $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity + 1 WHERE id = ?');
        $stmt->execute([$cart_item['id']]);
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare('INSERT INTO cart (user_id, product_id) VALUES (?, ?)');
        $stmt->execute([$user_id, $product_id]);
    }

}

// Retrieve items in the user's cart
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT cart.id, cart.quantity, products.name, products.price, products.image FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?');
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="produit_style.css">
    <title>Shopping Cart</title>
</head>

<body>
    <div class="navbar">
        <nav>
            <ul>
                <li><a href="cart.php">Mon panier</a></li>
                <li><a href="home.php">Mon compte</a></li>
            </ul>
        </nav>
    </div>

    <section class='trending-product' id='trending'>
        <div class='center-text'>
            <h2>Liste des <span>produits</span></h2>
        </div>
        <div class='products'>
            <?php foreach ($products as $product): ?>
                <div class='row'>
                    <img src="<?= 'image/' . $product['image'] ?>">
                    <div class='price'>
                        <h4>
                            <?php echo $product['name']; ?>
                        </h4>
                        <p>$
                            <?php echo $product['price']; ?>
                        </p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Ajouter au panier</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>


    </section>
</body>

</html>