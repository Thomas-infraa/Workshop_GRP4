<?php
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
    $requete = "SELECT first_name FROM USER WHERE email = '$email'";
    $result = mysqli_query($mysqli, $requete);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        $user = htmlspecialchars($userData['first_name']); // Récupérer et protéger le prénom
    } else {
        $user = "USER"; // Valeur par défaut si l'USER n'est pas trouvé
    }
} else {
    // Redirection vers la page de connexion si l'USER n'est pas connecté
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>


<head>
    <link rel="stylesheet" href="styletbm.css">
    <link rel="stylesheet" href="stylecalendar.css">
    <meta charset='utf-8' />

    <script src='index.global.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                initialDate: '2023-01-12',
                navLinks: true,
                businessHours: true,
                editable: true,
                selectable: true,
                events: [
                    {
                        title: 'Business Lunch',
                        start: '2023-01-03T13:00:00',
                        constraint: 'businessHours',
                        category: 'bde'
                    },
                    {
                        title: 'Meeting',
                        start: '2023-01-13T11:00:00',
                        constraint: 'availableForMeeting',
                        color: '#257e4a',
                        category: 'conference'
                    },
                    {
                        title: 'Conference',
                        start: '2023-01-18',
                        end: '2023-01-20',
                        category: 'conference'
                    },
                    {
                        title: 'Party',
                        start: '2023-01-29T20:00:00',
                        category: 'atelier'
                    }
                ]
            });

            calendar.render();

            // Gestion de l'ajout d'événements
            document.getElementById('eventForm').addEventListener('submit', function (e) {
                e.preventDefault(); 

                const title = document.getElementById('eventTitle').value;
                const date = document.getElementById('eventDate').value + 'T00:00:00'; // Utilise 00:00:00 pour une journée entière
                const category = document.getElementById('eventCategory').value;

                calendar.addEvent({
                    title: title,
                    start: date,
                    category: category
                });

                this.reset();
                filterEvents(category); // Filtrer après l'ajout
            });

            // Fonction de filtrage des événements
            function filterEvents(selectedCategory) {
                calendar.getEvents().forEach(function(event) {
                    if (event.extendedProps.category !== selectedCategory && selectedCategory !== '') {
                        event.setProp('display', 'none'); // Masque les événements qui ne correspondent pas à la catégorie sélectionnée
                    } else {
                        event.setProp('display', 'auto'); // Affiche les événements correspondants
                    }
                });
            }

            // Événement pour filtrer lorsque la catégorie change
            document.getElementById('eventCategory').addEventListener('change', function() {
                filterEvents(this.value);
            });
        });
    </script>
</head>
<header>
    <div class="header-content">
        <a class="btn-menu" href='principale.php' >Retour menu</a>
        <a href='principale.php?deconnexion=true' class="btn-logout">Déconnecter</a>
    </div>
</header>
<body>
    <h1>Votre Calendrier</h1>

    <form id="eventForm">
        <input type="text" id="eventTitle" placeholder="Titre de l'événement" required>
        <input type="date" id="eventDate" required>
        <select id="eventCategory" required>
            <option value="">Sélectionner une catégorie</option>
            <option value="bde">BDE</option>
            <option value="conference">Conférence</option>
            <option value="atelier">Atelier et Formation</option>
        </select>
        <button type="submit">Ajouter l'événement</button>
    </form>
    <div id='calendar'></div>

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
