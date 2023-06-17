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
        $new_quantity = $cart_item['quantity'] + 1;
        $stmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
        $stmt->execute([$new_quantity, $cart_item['id']]);

        // Remove item from cart if quantity reaches 0
        if ($new_quantity == 0) {
            $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ?');
            $stmt->execute([$cart_item['id']]);
        }
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)');
        $stmt->execute([$user_id, $product_id]);
    }
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];
    $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ?');
    $stmt->execute([$remove_id]);
}

// Update quantity of item in cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $new_quantity = $_POST['quantity'];
    $stmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
    $stmt->execute([$new_quantity, $update_id]);

    // Remove item from cart if quantity reaches 0
    if ($new_quantity == 0) {
        $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ?');
        $stmt->execute([$update_id]);
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
    <title>Cart</title>
</head>
<body>

    <div class='center-text'>
        <h2>Mon <span>Panier</span></h2>
    </div>
    <?php if (!empty($cart_items)): ?>
        <section class='trending-product' id='cart-container'>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total $</th>
                    <th>Action</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                    
                        <td><img src="<?= 'image/' . $item['image'] ?>"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="update_id" value="<?php echo $item['id']; ?>">
                                
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                <button type="submit" name="">Ok</button>
                            </form>
                        </td>
                        <td><?php echo $item['price'] * $item['quantity']; ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="remove_id" value="<?php echo $item['id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"></td>

                    <td></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <a href="product.php">Continuer vos achats</a>
        </section>
    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>
    
    <a href="product.php">Continuer vos achats</a>
</body>
</html>
