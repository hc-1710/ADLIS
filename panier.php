<?php
session_start();
$conn = mysqli_connect("127.0.0.1", "root", "1206", "base");

if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Ajouter un livre
if (isset($_GET['id']) && isset($_SESSION['idClient'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM livre WHERE id = $id");
    $livre = mysqli_fetch_assoc($result);

    if ($livre) {
        $idClient = $_SESSION['idClient'];

        $stmt = $conn->prepare("INSERT INTO panier (idClient, id_livre, titre, auteur, prix, prix_total, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissdds", $idClient, $livre['id'], $livre['titre'], $livre['auteur'], $livre['prix'], $livre['prix'], $livre['image']);

        if (!$stmt->execute()) {
            echo "Erreur lors de l'ajout au panier : " . $stmt->error;
        }

        $stmt->close();
    }
}

// Supprimer un livre
if (isset($_GET['remove']) && isset($_SESSION['idClient'])) {
    $id_livre = intval($_GET['remove']);
    $idClient = $_SESSION['idClient'];

    $stmt = $conn->prepare("DELETE FROM panier WHERE idClient = ? AND id_livre = ?");
    $stmt->bind_param("ii", $idClient, $id_livre);
    $stmt->execute();
    $stmt->close();
}

// Vider le panier
if (isset($_GET['clear']) && isset($_SESSION['idClient'])) {
    $idClient = $_SESSION['idClient'];

    $stmt = $conn->prepare("DELETE FROM panier WHERE idClient = ?");
    $stmt->bind_param("i", $idClient);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
        }
        h1 {
            margin-bottom: 20px;
        }
        .livre {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .livre-info {
            max-width: 80%;
        }
        .livre-actions a {
            background-color: #ff6961;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
        }
        .total {
            margin-top: 20px;
            font-size: 18px;
        }
        .actions {
            margin-top: 20px;
        }
        .actions a {
            margin-right: 10px;
            padding: 10px 15px;
            background-color: #7494ec;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>🛒 Mon Panier</h1>

<?php
if (isset($_SESSION['idClient'])) {
    $idClient = $_SESSION['idClient'];

    $req = $conn->prepare("SELECT * FROM panier WHERE idClient = ?");
    $req->bind_param("i", $idClient);
    $req->execute();
    $result = $req->get_result();

    if ($result->num_rows > 0):
        $total = 0;
        while ($livre = $result->fetch_assoc()):
            $total += $livre['prix'];
?>
    <div class="livre">
        <div class="livre-info">
            <strong><?= htmlspecialchars($livre['titre']) ?></strong> - <?= htmlspecialchars($livre['prix']) ?> DA
        </div>
        <div class="livre-actions">
            <a href="panier.php?remove=<?= $livre['id_livre'] ?>">Supprimer</a>
        </div>
    </div>
<?php endwhile; ?>

    <div class="total">
        <strong>Total :</strong> <?= $total ?> DA
    </div>

    <div class="actions">
        <a href="panier.php?clear=1">🗑️ Vider le panier</a>
        <a href="page_principale.php">← Continuer vos achats</a>
    </div>

<?php else: ?>
    <p>Votre panier est vide.</p>
    <a href="page_principale.php">← Retour à la boutique</a>
<?php endif;
} else {
    echo "<p>Vous devez vous connecter pour accéder au panier.</p>";
}
?>
</body>
</html>
