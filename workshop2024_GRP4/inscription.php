<?php

/* page: inscription.php */
// Connexion à la base de données :
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

// Par défaut, on affiche le formulaire
$AfficherFormulaire = 1;
$errorMessage = ""; // Variable pour stocker les erreurs

// Traitement du formulaire :
if (isset($_POST['last_name'], $_POST['first_name'], $_POST['email'], $_POST['password'])) {
    $last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = $_POST['password'];

    // Vérification du champ email
    if (empty($email)) {
        $errorMessage = "Le champ mail est vide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Le champ mail n'est pas un email valide.";
    } elseif (!preg_match("#@ecoles-epsi\.net$#", $email)) { // Vérifier si l'email se termine bien par @ecoles-epsi.net
        $errorMessage = "Le mail doit se terminer par '@ecoles-epsi.net'.";
    }

    // Vérification du champ mot de passe
    elseif (empty($password)) {
        $errorMessage = "Le champ Mot de passe est vide.";
    } else {
        // Vérifier si cet email est déjà utilisé
        $requete = "SELECT * FROM USER WHERE email = '$email'";
        $result = mysqli_query($mysqli, $requete);

        if (!$result) {
            // Afficher l'erreur SQL si la requête échoue
            $errorMessage = "Erreur lors de l'exécution de la requête : " . mysqli_error($mysqli);
        } elseif (mysqli_num_rows($result) == 1) {
            $errorMessage = "Ce mail est déjà utilisé.";
        } else {
            // Toutes les vérifications sont faites, on passe à l'enregistrement dans la base de données
            $password_hash = md5($password); // Cryptage du mot de passe
            $insert_query = "INSERT INTO USER (last_name, first_name, email, password) 
                             VALUES ('$last_name', '$first_name', '$email', '$password_hash')";

            if (!mysqli_query($mysqli, $insert_query)) {
                $errorMessage = "Une erreur s'est produite lors de l'inscription : " . mysqli_error($mysqli);
            } else {
                // Affichage du message de succès et redirection
                echo "<script>alert('Vous êtes inscrit avec succès !'); window.location.href = 'principale.php';</script>";
                // On n'affiche plus le formulaire
                $AfficherFormulaire = 0;
            }
        }
    }

    // Si une erreur a été détectée, on l'affiche dans une pop-up
    if ($errorMessage !== "") {
        // Échappez les apostrophes pour éviter les erreurs de syntaxe
        $escapedErrorMessage = addslashes($errorMessage);
        echo "<script>alert('$escapedErrorMessage');</script>";
    }
}

if ($AfficherFormulaire == 1) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="login.css" media="screen" type="text/css" />
        <title>Inscription</title>
    </head>
    <body>
        <form method="post" action="inscription.php">
            <h1>Inscription</h1>
            Nom : <input type="text" name="last_name" required>
            <br />
            Prénom : <input type="text" name="first_name" required>
            <br />
            Mail : <input type="text" name="email" required>
            <br />
            Mot de passe : <input type="password" name="password" required>
            <br />
            <input type="submit" value="S'inscrire">
        </form>
    </body>
    </html>
    <?php
}
?>
