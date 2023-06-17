<?php
// Connect to MySQL database
$pdo = new PDO('mysql:host=localhost; dbname=test_db', 'root', '');

// Ajouter

if (isset($_POST['add_btn'])) {
    // Retrieve data from form submission
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // ajouter un nouvelle produit on data 
    $stmt = $pdo->prepare('INSERT INTO products (name, price, image) VALUES (?, ?, ?)');
    $stmt->execute([$name, $price, $image]);
}

// Supprimer
if (isset($_POST['delete_btn'])) {
    // Retrieve the ID of the product to delete
    $delete_id = $_POST['delete'];

    // Delete the product from the database
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$delete_id]);
}




// Retrieve the products from the database

$sql = "SELECT * FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des produits</title>
    <link rel="stylesheet" href="produit_style.css">
</head>
<body>
<section >
    <div class='center-text'>
        <h2>Gestion des <span>produits</span></h2>
    </div>
    <!--html de Ajouter -->
    <form method="POST">
        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Prix:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        

        <button type="submit" name="add_btn">Ajouter</button>
        
    </form>

    <!--html de Supprimer -->
    <form method="POST">
    <label for="delete">Sélectionner le produit à supprimer:</label>
    <select id="delete" name="delete">
        <?php foreach ($result as $row): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" name="delete_btn">Supprimer</button>
</form>


<!--table -->
    </section>
    <section class='product-table' id='cart-container'>
    <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom de produit</th>
                    <th>Prix</th>
                    <th>Image</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><img src="<?= 'image/' . $row['image'] ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody> 
    </table>      
    </section>
</body>
</html>