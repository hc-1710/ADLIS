<?php
$conn = mysqli_connect("localhost", "root", "celia123", "adlis_base");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Créer le dossier uploads s'il n'existe pas
if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}

// Ajouter un livre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $titre = $_POST['nom'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO livre (titre, auteur, description, prix, image) VALUES ('$titre', '$auteur', '$description', '$prix', '$target')";
            $conn->query($sql);
        } else {
            echo "Erreur lors du déplacement du fichier.";
        }
    } else {
        echo "Erreur avec le fichier image.";
    }
}
// Supprimer un livre
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM livre WHERE id=$id");
    header("Location: ajout_produit.php");
}

// Modifier un livre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $conn->query("UPDATE livre SET titre='$titre', auteur='$auteur', description='$description', prix='$prix' WHERE id=$id");
    header("Location: page_principale.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ajouter vos produits</title>
        <link rel="icon" href="ADLIS_logo_transparent (1).png" width="100%" type="image/svg+xml">
    </head>
    <body>
    <div class="container">
        <div class="admin-product">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                <h5>Ajouter un nouveau livre</h5>
                <label for="titre">Titre:</label>
                <input type="text" name="nom" placeholder="Nom du livre" class="box" required>
                <label for="auteur">Auteur:</label>
                <input type="text" name="auteur" placeholder="L'auteur du livre" class="box" required>
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Description du livre" class="box" required></textarea>
                <label for="prix">Prix:</label>
                <input type="text" name="prix"min="200" max= "2000" placeholder="Prix : DA" class="box" required>
                <label for="image" id="imagebutton">clicker pour ajouter  l'image du livre </label>
                <input type="file" name="image" class="box" id="fileInput"  hidden required>
                <label for="categorie">Catégorie:</label>
                <select name="categorie" required>
                <option value="1">Science</option>
                <option value="2">Informatique</option>
                <option value="3">Romans</option>
                <option value="4">Histoire</option>
                </select>
                <input type="submit" name="submit" value="Ajouter" class="btn">
            </form>
        </div>
   </div>

<h2>Liste des Livres</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Description</th>
        <th>Prix</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php 
    $result = $conn->query("SELECT * FROM livre");
    if ($result) {
        while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['titre'] ?></td>
                <td><?= $row['auteur'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['prix'] ?> DA</td>
                <td><img src="<?= $row['image'] ?>" width="100"></td>
                <td>
                    <a href="?edit=<?= $row['id'] ?>">Modifier</a> |
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer ce livre ?');">Supprimer</a>
                    <a href="confirmer.php?id=<?= $row['id'] ?>" class="btn btn-success">Confirmer</a>

                </td>
            </tr>
        <?php endwhile; 
    } else {
        echo "<tr><td colspan='7'>Aucun livre trouvé.</td></tr>";
    }
    ?>
</table>

<?php if (isset($_GET['edit'])) :
    $id = intval($_GET['edit']);
    $query = $conn->query("SELECT * FROM livre WHERE id=$id");
    if ($query) {
        $livre = $query->fetch_assoc();
        if ($livre) : ?>
            <h2>Modifier un Livre</h2>
            <form method="POST" class="modifier">
                <input type="hidden" name="id" value="<?= $livre['id'] ?>">
                <input type="text" name="titre" value="<?= $livre['titre'] ?>" required><br>
                <input type="text" name="auteur" value="<?= $livre['auteur'] ?>" required><br>
                <textarea name="description" required><?= $livre['description'] ?></textarea><br>
                <input type="number" min="200" max= "2000"name="prix" value="<?= $livre['prix'] ?>" required><br>
                <button type="submit" name="modifier">Modifier</button>
            </form>
        <?php endif; 
    } else {
        echo "<p>Erreur lors de la récupération des données.</p>";
    }
endif; ?>
<?php $conn->close(); ?>
</body>
</html>
<style>
:root{
    --bg-color:#f4f4f4;
    --black:#333;
}
body {
    font-family: Arial, sans-serif;
    background-color:#f4f4f4;
    text-align: center;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    background-color:rgb(235, 237, 243);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0px 4px 10px #7494ec;
    margin: 50px auto;
}

.admin-product form h5 {
    text-transform: uppercase;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}

label {
    font-weight: bold;
}

input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

input[type="file"] {
    border: none;
}

input[type="submit"] {
    background:#e9eb6f;
    color: black;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
}

input[type="submit"]:hover {
    background: #7494ec;
}
#imagebutton{
    background:#eaebb8;
    color: black;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
}
#imagebutton:hover {
    background: #7494ec;
}
select {
    width: 100%;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: white;
    cursor: pointer;
    transition: border-color 0.3s;
}

select:hover, select:focus {
    border-color: #007bff;
    outline: none;
}
/* Amélioration du tableau */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-family: Arial, sans-serif;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #7494ec;
    color: white;
    text-transform: uppercase;
}
td a {
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    background-color:#eaebb8;
    display: grid;
    box-shadow :2px 2px 5px black;

}
button {
    background-color:#eaebb8;
    color: black;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}
.modifier{
    padding: 10px;
    margin-top: 20px;
}

/* Style pour l'entrée de catégorie */
select, input[type="text"], input[type="number"], input[type="file"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    margin-bottom: 10px;
}
</style>

<script>
document.getElementById("imagebutton").addEventListener("click", function() {
    document.getElementById("fileInput").click();
});

 document.getElementById("myForm").addEventListener("submit", function(event) {
    let input = document.getElementById("number");
    let max = parseInt(input.max);
    if (input.value > max) {
      alert("Le nombre ne doit pas dépasser " + max + " DA!");
      event.preventDefault();
    }
});
</script>