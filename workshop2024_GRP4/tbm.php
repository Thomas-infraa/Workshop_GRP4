<?php
// Récupérer les données depuis l'API
$response = file_get_contents("https://gateway-apim.infotbm.com/maas-web/web/v1/traffic-info?filter=TODAY");
$response = json_decode($response);

// Vérifier que la réponse contient des modes
if (!isset($response->global->modes) || empty($response->global->modes)) {
    echo "Aucun mode disponible.";
    exit;
}

// Fonction pour traduire les sévérités
function translateSeverity($severity) {
    switch ($severity) {
        case "DISTURBED":
            return "Perturbé";
        case "INFORMATIVE":
            return "Informatif";
        default:
            return $severity;  // On renvoie la valeur brute s'il y a une sévérité inattendue
    }
}

session_start(); // Démarre la session

// Vérification si l'USER veut se déconnecter
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

// Vérification de la session pour récupérer l'email de l'USER
if (isset($_SESSION['email']) && $_SESSION['email'] !== "") {
    $email = htmlspecialchars($_SESSION['email']); // Protection contre les attaques XSS

    // Récupérer le prénom (first_name) de l'USER à partir de la base de données
    $requete = "SELECT first_name FROM user WHERE email = '$email'";
    $result = mysqli_query($mysqli, $requete);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $user = htmlspecialchars($userData['first_name']); // Récupérer et protéger le prénom
    } else {
        $user = "user"; // Valeur par défaut si l'USER n'est pas trouvé
    }
} else {
    // Redirection vers la page de connexion si l'USER n'est pas connecté
    header("Location: login.php");
    exit;
}

// Commencer à construire le contenu HTML
$htmlContent = <<<HTML
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <link rel="stylesheet" href="styletbm.css">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Informations de Trafic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; position: sticky; top: 0; z-index: 2; }
        #tableContainer { 
            width: 100%; 
            height: 600px; /* Hauteur visible du tableau */
            overflow: hidden; /* Masquer la barre de défilement */
        }
        #scrollingContent {
            height: 100%;
        }
    </style>
</head>
<body>
<header>
    <div class="header-content">
        <a class="btn-menu" href='principale.php' >Retour menu</a>
        <a href='principale.php?deconnexion=true' class="btn-logout">Déconnecter</a>
    </div>
</header>
    <h1>Informations Trafic</h1>
    <div id="tableContainer">
        <table>
            <thead>
                <tr>
                    <th>Icone</th>
                    <th>Nom</th>
                    <th>En Service</th>
                    <th>Sévérité</th>
                </tr>
            </thead>
            <tbody id="scrollingContent">
HTML;

// Accéder aux informations de chaque mode
foreach ($response->global->modes as $mode) {
    // Pour chaque mode, parcourir les lignes
    foreach ($mode->lines as $line) {
        // Créer une URL dynamique basée sur le code de la ligne
        $url = "https://www.infotbm.com/fr/perturbations/ligne/{$line->id}?time_ref=inProgress&_rsc=q3zpd";

        // Traduire la sévérité
        $translatedSeverity = translateSeverity($line->severity);

        // Traduire le statut d'opération en 'Oui' ou 'Non'
        $isOperating = $line->isOperating ? "Oui" : "Non";

        // Ajouter les informations de la ligne au contenu HTML, y compris le lien cliquable
        $htmlContent .= <<<HTML
                <tr>
                    <td><a href='$url' target='_blank'><img src='{$line->iconUrl}' alt='{$line->name}' width='30'></a></td>
                    <td>{$line->name}</td>
                    <td>$isOperating</td>
                    <td>$translatedSeverity</td>
                </tr>
HTML;
    }
}

// Finir le contenu HTML avec JavaScript pour le défilement
$htmlContent .= <<<HTML
            </tbody>
        </table>
    </div>

    <script>
        // Fonction pour faire défiler le contenu vers le haut
        function autoScroll() {
            let tableContainer = document.getElementById('tableContainer');
            let scrollingContent = document.getElementById('scrollingContent');
            let scrollSpeed = 1.2;  // Vitesse de défilement en pixels

            // Faire défiler vers le haut de scrollSpeed à intervalles réguliers
            setInterval(function() {
                tableContainer.scrollTop += scrollSpeed;
                // Si on atteint la fin du tableau, revenir en haut
                if (tableContainer.scrollTop >= scrollingContent.offsetHeight - tableContainer.offsetHeight) {
                    tableContainer.scrollTop = 0;
                }
            }, 15);  // Ajuster l'intervalle pour contrôler la vitesse
        }

        // Appeler la fonction pour démarrer le défilement
        autoScroll();
    </script>
<footer>
    <div class="footer-container">
        <div class="footer-contact">
            <p>Besoin d'aide ? Contactez-nous à : <a href="mailto:contact@ecole.com">aide.contact@ecoles-epsi.net</a></p>
            <p>114 Rue Lucien Faure, 33000 Bordeaux / 8 avenue de Terrefort, Bruges 33520</p>
        </div>
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
HTML;

// Afficher le contenu HTML directement
echo $htmlContent;
?>
