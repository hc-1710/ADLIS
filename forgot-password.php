<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = htmlspecialchars($_POST['email']);

    // Connexion à la base de données
    $mysqli = new mysqli("localhost", "root", "celia123", "adlis_base");
    if ($mysqli->connect_error) {
        die("Erreur de connexion : " . $mysqli->connect_error);
    }

    // Vérification de l'email
    $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Générer un token unique (ex : pour lien de réinitialisation)
        $token = bin2hex(random_bytes(32));

        // Stocker le token (tu peux ajouter un champ dans la base de données, ex : reset_token)
        $update = $mysqli->prepare("UPDATE utilisateur SET reset_token = ? WHERE email = ?");
        $update->bind_param("ss", $token, $email);
        $update->execute();

        // Envoyer un email (simple version, à adapter selon ton hébergeur)
        $resetLink = "http://localhost/ton-projet/reset-password.php?token=$token";
        $subject = "Réinitialisation de votre mot de passe";
        $body = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink";
        $headers = "From: no-reply@votresite.com";

        // (Facultatif) Envoi réel
        // mail($email, $subject, $body, $headers);

        $message = "Un lien de réinitialisation a été envoyé à votre adresse email.";
    } else {
        $message = "Adresse email non trouvée.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

