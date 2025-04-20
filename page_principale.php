<?php
session_start();
$conn = mysqli_connect("127.0.0.1", "root", "celia123", "adlis_base");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer tous les livres
$sql = "SELECT * FROM livre ORDER BY id DESC"; 
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <div class="logo_name">ADLIS</div>
            <img src="ADLIS_logo_transparent (1).png" alt="profileImg">
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Mon profil </span>
                </a>
                <span class="tooltip">Mon profil</span>
            </li>
            <li>
                <a href="AVIS.html">
                    <i class='bx bx-chat'></i>
                    <span class="links_name">Avis</span>
                </a>
                <span class="tooltip" >Avis</span>
            </li>
            <li>
                <a href="">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="links_name">Analytics</span>
                </a>
                <span class="tooltip">Analytics</span>
            </li>
            <li>
                <a href="ajout_produit.php">
                    <i class='bx bx-folder'></i>
                    <span class="links_name" >Ajouter un livre</span>
                </a>
                <span class="tooltip">ajouter un livre</span>
            </li>
            <li>
                <a href="">
                    <i class='bx bx-cart-alt'></i>
                    <span class="links_name">Panier</span>
                </a>
                <span class="tooltip">Panier</span>
            </li>
            <li>
                <a href="">
                    <i class='bx bx-heart'></i>
                    <span class="links_name">Saved</span>
                </a>
                <span class="tooltip">Saved</span>
            </li>
            <li>
                <a href="">
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Settings</span>
                </a>
                <span class="tooltip">Settings</span>
            </li>
            <li class="profile">
                <div class="profile-details">
                   
                    <div class="name_job">
                        <div class="name"> LOG OUT</div>
                        <div class="job"></div>
                    </div>
                </div>
                <i class='bx bx-log-out' id="log_out"></i>
            </li>
        </ul>
    </div>
    <div class="main-content">
    <?php if ($result->num_rows > 0): ?>
    <?php while ($livre = $result->fetch_assoc()): ?>
        <?php
$description = explode(' ', $livre['description']);
        
// Vérifier si la description a moins de 3 mots
    $short_description = implode(' ', array_slice($description, 0, 3)) . '...';
?>
        <div class="livre-info">
            <p><strong>Titre :</strong> <?= htmlspecialchars($livre['titre']) ?></p>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
            <p class="description"><strong>Description :</strong> 
                <span class="short"><?= $short_description?></span>
        <span class="full" style="display: none;" ><?= nl2br(htmlspecialchars($livre['description'])) ?></span> </p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($livre['prix']) ?> DA</p>
            <img src="<?= htmlspecialchars($livre['image']) ?>" alt="Image du livre">
            <br>
            <a href="panier.php?id=<?= $livre['id'] ?>" class="back-btn" method="post" action="panier.php">Ajouter au panier</a>
            <a class="toggle-desc">Lire plus</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucun livre disponible.</p>
<?php endif; ?>
    </div>
    <script>
    document.querySelectorAll('.toggle-desc').forEach(button => {
    button.addEventListener('click', function () {
        let description = this.closest('.livre-info'); // Trouver le bon parent
        let shortText = description.querySelector('.short');
        let fullText = description.querySelector('.full');

        if (fullText.style.display === "none") {
            fullText.style.display = "inline";
            shortText.style.display = "none";
            this.textContent = "Lire moins";
        } else {
            fullText.style.display = "none";
            shortText.style.display = "inline";
            this.textContent = "Lire plus";
        }
    });
});
    </script>
</body>
</html>
<script>
let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
})

searchBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
})

function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
        closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
}
menuBtnChange();
</script>
<style>
 * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 78px;
    background:#7494ec;
    padding: 6px 14px;
    z-index: 1;
    transition: all 0.5s ease;
}

.sidebar.open {
    width: 250px;
}

.sidebar .logo-details {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.sidebar .logo-details .logo_name {
    color: #fff;
    font-size: 20px;
    font-weight: 600;
    opacity: 0;
    transition: all 0.5s ease;
}

.sidebar.open .logo-details,
.sidebar.open .logo-details .logo_name {
    opacity: 1;
}

.sidebar .logo-details #btn {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    font-size: 22px;
    text-align: center;
    cursor: pointer;
    transition: all 0.5s ease;
}

.sidebar.open .logo-details #btn {
    text-align: center;
}

.sidebar i {
    color: #fff;
    height: 60px;
    min-width: 50px;
    font-size: 28px;
    text-align: center;
    line-height: 60px;
}

.sidebar .nav-list {
    margin-top: 20px;
    height: 100%;
}

.sidebar li {
    position: relative;
    margin: 8px 0;
    list-style: none;
}

.sidebar input {
    font-size: 15px;
    color: #fff;
    font-weight: 400;
    outline: none;
    height: 50px;
    width: 100%;
    border: none;
    border-radius: 12px;
    transition: all 0.5s ease;
    background: #acbff3;
}

.sidebar.open input {
    padding: 0 20px 0 50px;
    width: 100%;
}

.sidebar .bx-search {
    position: absolute;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    font-size: 22px;
    background:#acbff3;
    color: #fff;
    border-color: #fff;
}

.sidebar .bx-search:hover {
    background: #fff;
    color: #acbff3;
}

.sidebar.open .bx-search:hover {
    background:#acbff3;
    color: #fff;
}

.sidebar li i {
    height: 50px;
    line-height: 50px;
    font-size: 18px;
    border-radius: 12px;
}

.sidebar li a {
    display: flex;
    height: 100%;
    width: 100%;
    border-radius: 12px;
    align-items: center;
    text-decoration: none;
    transition: all 0.4s ease;
    background: #7494ec;
}

.sidebar li a:hover {
    background:#eaebb8;
}

.sidebar li a .links_name {
    color: #fff;
    font-size: 15px;
    font-weight: 400;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: 0.4s;
}

.sidebar.open li a .links_name {
    opacity: 1;
    pointer-events: auto;
}

.sidebar li a:hover .links_name,
.sidebar li a:hover i {
    transition: all 0.5s ease;
    color: #11101D;
}

.sidebar li .tooltip {
    position: absolute;
    top: -20px;
    left: calc(100% + 15px);
    background: #fff;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 400;
    opacity: 0;
    white-space: nowrap;
    pointer-events: none;
    transition: 0s;
}

.sidebar li:hover .tooltip {
    opacity: 1;
    pointer-events: auto;
    transition: all 0.4s ease;
    top: 50%;
    transform: translateY(-50%);
}

.sidebar.open li .tooltip {
    display: none;
}

.sidebar li.profile {
    position: fixed;
    height: 60px;
    width: 78px;
    left: 0;
    bottom: -8px;
    padding: 10px 14px;
    background:#7494ec;
    transition: all 0.5s ease;
    overflow: hidden;
}

.sidebar.open li.profile {
    width: 250px;
}

.sidebar li .profile-details {
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
}

.sidebar .logo-details img {
    height: 250px;
    width: 250px;
    object-fit: contain;
    border-radius: 6px;
    margin-left: 10px;
    position: relative;
    padding: 40px;
    margin-top: 20px;
    right: 55px;
}
.sidebar .logo-details .logo_name{
    position: relative;
    font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    font-size: 24px;
    left:10%;
}

.sidebar li.profile .name,
.sidebar li.profile .job {
    font-size: 20px;
    font-weight: 400;
    color: #fff;
    white-space: nowrap;
    position: relative;
    left:110px;
    margin-top:7px;
    font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
}

.sidebar li.profile .job {
    font-size: 12px;
   
}

.sidebar .profile #log_out {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    background:rgb(192, 205, 240);
    width: 100%;
    height: 60px;
    line-height: 60px;
    transition: all 0.5s ease;
}

.sidebar.open .profile #log_out {
    width: 50px;
    background: none;
}

.home-section {
    position: relative;
    background: #e4e9f7;
    min-height: 100vh;
    top: 0;
    left: 78px;
    width: calc(100% -78px);
    transition: all 0.5s ease;
}

.sidebar.open~.home-section {
    left: 250px;
    width: calc(100%-250px);
}


.livre-info {
    width: 250px; /* Largeur fixe pour chaque livre */
    background-color: rgb(235, 237, 243);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    position: relative;
    height: auto; 
    white-space: normal;
    overflow-wrap: anywhere;
}
.livre-info:hover {
    transform: scale(0.1); /* Agrandit légèrement la carte */
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
    z-index: 10;
    transform: scale(1.2) rotate(1deg); /* Légère rotation */
    height: auto;
   
}
.full{
    display: none;
   
    
}
.livre-info p {
    font-size: 16px;
    color: #555;
    margin: 5px 0;

}

.livre-info img {
    width: 150px;
    height: 100px;
    border-radius: 10px;
    margin-top: 10px;
    box-shadow: 2px 2px 5px black;
    align-items: center;
    align-content: center;

}

.main-content{
display: flex;
    flex-wrap: wrap; 
    justify-content: flex-start;
    gap: 20px; 
    margin-top: 80px;
    margin-left: 100px;
    
}
.back-btn,.toggle-desc {
    display: inline-block;
    padding: 10px 20px;
    background-color: #eaebb8;
    color:black;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 15px;
    box-shadow: 2px 2px 5px black;
}
</style>