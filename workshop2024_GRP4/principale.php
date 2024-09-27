<?php
session_start(); // Démarre la session

// Vérification si l'utilisateur veut se déconnecter
if (isset($_GET['deconnexion']) && $_GET['deconnexion'] == true) {
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session
    header("Location: login.php"); // Redirige vers la page de connexion
    exit; // S'assure que le script s'arrête après la redirection
}

// Connexion à la base de données
$BDD = array();
$BDD['host'] = "localhost";
$BDD['user'] = "root";
$BDD['pass'] = "root";
$BDD['db'] = "ForumBDD";
$mysqli = mysqli_connect($BDD['host'], $BDD['user'], $BDD['pass'], $BDD['db']);

if (!$mysqli) {
    echo "<script>alert('Connexion non établie.');</script>";
    exit;
}

// Vérification de la session pour récupérer l'email de l'utilisateur
if (isset($_SESSION['email']) && $_SESSION['email'] !== "") {
    $email = htmlspecialchars($_SESSION['email']); // Protection contre les attaques XSS

    // Récupérer le prénom (first_name) de l'utilisateur à partir de la base de données
    $requete = "SELECT first_name FROM user WHERE email = '$email'";
    $result = mysqli_query($mysqli, $requete);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $user = htmlspecialchars($userData['first_name']); // Récupérer et protéger le prénom
        $_SESSION["username"]=$user;
    } else {
        $user = "Utilisateur"; // Valeur par défaut si l'utilisateur n'est pas trouvé
    }
} else {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styleprincipale.css"> <!-- Assurez-vous d'utiliser votre feuille de style -->
    <title>Page Principale</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

</head>
<body>

<header>
    <div class="content">
        <h1>Bienvenue dans votre espace <?php echo $user; ?></h1>
        <div class="button-container">
            <a href="forum.php" class="btn">Forum</a>
            <a href="evenements.php" class="btn">Calendrier</a>
            <a href="tbm.php" class="btn">Info traffic TBM</a>
        </div>
    </div>
    <div class="user-menu">
        <span class="user-name">Bonjour, <strong><?php echo $user; ?></strong></span>
        <a href='principale.php?deconnexion=true' class="btn-logout">Déconnecter</a>
    </div>
</header>

<section class="carousel-section">
    <div class="carousel">
        <div class="carousel-container">
            <div class="carousel-slide">
                <img src="image/téléchargement1.jpg" alt="Image 1">
                <div class="carousel-text">
                    <h2>Nouveau Campus !</h2>
                    <p>Le nouveau campus de Bruges ouvre ses portes le premier Octobre</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="image/600x360.png" alt="Image 2">
                <div class="carousel-text">
                    <h2>Nouvelle mascotte</h2>
                    <p>Retrouvez notre nouvelle mascotte sur les prochains salon étudiant</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="image/600x360.png" alt="Image 3">
                <div class="carousel-text">
                    <h2>Rentrée des M1</h2>
                    <p>La dernière promo du campus de Bordeaux a fait sa rentrée aujourd'hui !</p>
                </div>
            </div>
        </div>
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div>
</section>

<script src="script.js"></script>
<footer>
    <style>
        #map { height: 400px;
            width: 40%;
         }
    </style>
    <div class="footer-container">
        <div class="footer-contact">
            <p>Besoin d'aide ? Contactez-nous à : <a href="mailto:contact@ecole.com">aide.contact@ecoles-epsi.net</a></p>
            <p>114 Rue Lucien Faure, 33000 Bordeaux / 8 avenue de Terrefort, Bruges 33520</p>
        </div>
        <div id="map"></div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="script.js"></script>
        <div class="footer-social">
            <a href="https://www.instagram.com/votre_ecole" target="_blank">
                <img src="image/instagram-icon.jpg" alt="Instagram">
            </a>
            <a href="https://www.facebook.com/votre_ecole" target="_blank">
                <img src="image/facebook-icon.png" alt="Facebook">
            </a>
        </div>

    </div>
</footer>

</body>

</html>
<script>
    const map = L.map('map').setView([44.8651900, -0.5617887 ], 12); // Coordonnées du campus

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 16,
}).addTo(map);

// Ajouter des marqueurs pour les bâtiments
const buildings = [
    { name: 'Campus Bassins à flot', coords: [44.8651900, -0.5602887 ] },
    { name: 'Campus Bruges', coords: [44.8893813, -0.6137157] },
];

buildings.forEach(building => {
    L.marker(building.coords)
        .addTo(map)
        .bindPopup(building.name)
        .openPopup();
});

</script>
