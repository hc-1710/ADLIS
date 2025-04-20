<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli("localhost", "root", "celia123", "adlis_base");

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $motDePasse = $_POST['motDePasse'];

    $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE nom = ?");
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($motDePasse, $user['motDePasse'])) {
            $_SESSION['nom'] = $user['nom'];
             $_SESSION['idClient'] = $user['id']; // Enregistrer l'ID de l'utilisateur

            // Mettre à jour la table 'panier' avec l'ID du client (utilisateur connecté)
            $idClient = $user['id'];
            $updatePanierStmt = $mysqli->prepare("UPDATE panier SET idClient = ? WHERE idClient IS NULL");
            $updatePanierStmt->bind_param("i", $idClient);
            $updatePanierStmt->execute();
            $updatePanierStmt->close();
             header("Location: page_principale.php");
             exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
      // Utilisateur non trouvé
     echo "Utilisateur non trouvé.";
    }

    $stmt->close();
}

$mysqli->close();
?>
