<?php
$conn = mysqli_connect("localhost", "root", "celia123", "adlis_base");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si un ID est présent
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Stocker les IDs confirmés dans une session pour les conserver
    session_start();
    if (!isset($_SESSION['confirmed_ids'])) {
        $_SESSION['confirmed_ids'] = [];
    }
    
    // Ajouter l'ID si pas déjà présent
    if (!in_array($id, $_SESSION['confirmed_ids'])) {
        $_SESSION['confirmed_ids'][] = $id;
    }

    // Construire l'URL avec plusieurs IDs
    $id_list = implode(',', $_SESSION['confirmed_ids']);
    header("Location: page_principale.php?id=" . $id_list);
    exit();
} else {
    echo "ID du livre non spécifié.";
    exit();
}
?>
