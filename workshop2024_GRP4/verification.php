<?php
session_start();

// Connexion à la base de données
$db_username = 'root';
$db_password = 'root';
$db_name     = 'ForumBDD';
$db_host     = 'localhost';
$db = mysqli_connect($db_host, $db_username, $db_password, $db_name) or die('could not connect to database');

// Vérifier que le formulaire a bien été soumis
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email']));
    $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password']));
    
    if ($email !== "" && $password !== "") {
        // Hachage du mot de passe
        $password_hash = md5($password);
        
        // Vérification des informations
        $requete = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($db, $requete);
        
        if (!$result) {
            die('Erreur SQL : ' . mysqli_error($db));
        }
        
        // Vérification si l'email existe
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Comparer le mot de passe haché avec celui stocké
            if ($user['password'] === $password_hash) {
                // Connexion réussie
                $_SESSION['email'] = $email;
                header('Location: principale.php');
                exit();
            } else {
                // Mot de passe incorrect
                header('Location: login.php?erreur=1');
                exit();
            }
        } else {
            // Email non trouvé
            header('Location: login.php?erreur=1');
            exit();
        }
    } else {
        // Si l'email ou le mot de passe est vide, rediriger avec une autre erreur
        header('Location: login.php?erreur=2');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
