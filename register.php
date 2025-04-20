<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli("localhost", "root", "celia123", "adlis_base");

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom'], $_POST['email'], $_POST['motDePasse'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $motDePasse = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO utilisateur (nom, email, motDePasse) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $email, $motDePasse);

    if ($stmt->execute()) {
        header("Location: page_principale.php");
             exit();
    } else {
        echo "❌ Erreur : " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
