<?php
session_start();

// Vérifier si le panier existe, sinon le créer
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un livre au panier
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])) {
    $_SESSION['panier'][] = [
        'id' => $_POST['id'],
        'titre' => $_POST['titre'],
        'prix' => $_POST['prix'],
        'image' => $_POST['image'],
    ];

    // Vérifier si le livre est déjà dans le panier (évite les doublons)
    $existe = false;
    foreach ($_SESSION['panier'] as $item) {
        if ($item['id'] == $livre['id']) {
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $_SESSION['panier'][] = $livre;
    }
}

// Supprimer un livre du panier
if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])) {
    $_SESSION['panier'] = array_filter($_SESSION['panier'], function ($item) {
        return $item['id'] != $_GET['id'];
    });
}

// Calculer le total
$prix_total = 0;
foreach ($_SESSION['panier'] as $item) {
    $prix_total += $item['prix'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Mon Panier</h2>
        <form method="post" action="">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="Livre"></td>
                            <td><?= htmlspecialchars($item['titre']) ?></td>
                            <td class="price"><?= htmlspecialchars($item['prix']) ?> DA</td>
                            <td><input type="number" name="quantities[<?= $id ?>]" class="quantity" value="<?= $item['quantite'] ?>" min="1"></td>
                            <td class="item-total"><?= $item['prix'] * $item['quantite'] ?> DA</td>
                            <td>
                                <button type="submit" name="remove" value="<?= $id ?>" class="remove-btn">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-summary">
                <h3>Total : <span id="cart-total"><?= $prix_total ?> DA</span></h3>
                <button type="submit" name="update" class="checkout-btn">Mettre à jour</button>
                <button class="checkout-btn">Passer la commande</button>
            </div>
        </form>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    .container {
        max-width: 800px;
        margin: auto;
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table th, .cart-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .cart-table img {
        width: 80px;
        height: 100px;
        object-fit: cover;
    }
    .cart-summary {
        text-align: right;
        margin-top: 20px;
    }
    .checkout-btn, .remove-btn {
        background-color: #ff5733;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    .checkout-btn:hover, .remove-btn:hover {
        background-color: #c4421e;
    }
</style>