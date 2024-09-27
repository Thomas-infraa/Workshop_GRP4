<?php 
    date_default_timezone_set('Europe/Paris');
    session_start();
    unset($_SESSION["lastpostseen"]);
    unset($_SESSION["lastquestionpost"]);
    unset($_SESSION["lastauthorpost"]);
    unset($_SESSION["lastposteddate"]);

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



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleForum.css">
    <title>Epsi-View | Forum</title>
    <script type="text/javascript" src="main.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <a class="btn-menu" href='principale.php' >Retour menu</a>
            <a href='principale.php?deconnexion=true' class="btn-logout">Déconnecter</a>
        </div>
    </header>
    <h1>Forum</h1>
    <div>
        <div class="postsearch">
            <h4>Rechercher un post :</h4>
            <form action="forum.php" method="post">
                <input type="text" name="searchpostinput">
                <button type="submit" class="btn-menu">Rechercher</button>
            </form>
        </div>
        <button id="newpostbutton" onclick="clicknewpost()" class="btn-menu" style="margin-left: 1rem;">Nouveau Post</button>
        <div class="newpostbox" id="newpostbox" style="display: none;">
            <form action="post.php" method="post">
                <label><strong>Demande :</strong></label>
                <input type="text" name="askpost"></input>
                <input type="text" name="user" value="<?php echo($_SESSION["username"])?>" hidden></input>
                <button type="submit" class="btn-menu">Envoyer</button>
            </form>
        </div>
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "root";
                $dbname = "ForumBDD";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }                
                if(!isset($_POST["searchpostinput"])){$get_posts = "SELECT idpost, namepost, authorpost, datepost FROM post ORDER BY idpost DESC";
                    $result = $conn->query($get_posts);
                    if($result->num_rows > 0){
                        while($result_row = $result->fetch_assoc()){
                            $idpost = $result_row["idpost"];
                            $questionpost = $result_row["namepost"];
                            $authorpost = $result_row["authorpost"];
                            $post_time = strtotime($result_row['datepost']);
                            print_r (
                                '       <div class="forumboxhover">
                                            <form id="accesspost'.$idpost.'" action="conv.php" method="post">
                                                <input type="text" name="idpost" value="'.$idpost.'" hidden></input>
                                                <input type="text" name="questionpost" value="'.$questionpost.'" hidden></input>
                                                <input type="text" name="authorpost" value="'.$authorpost.'" hidden></input>
                                                <input type="text" name="posteddate" value="'.date('j/m/Y | H:i', $post_time).'" hidden></input>
                                            </form>
                                            <div class="forumbox" onclick=accessform('.$idpost.')>
                                                <h3>'.$questionpost.'</h3>
                                            
                                                <span>Écris par '.$authorpost.'</span><br>
                                                <span>'.date('j/m/Y | H:i', $post_time).'</span>
                                            </div>
                                        </div>
                                '
                            );
                        };
                    };
                }else{
                    $research = mysqli_real_escape_string($conn, htmlspecialchars($_POST['searchpostinput']));
                    $get_posts = "SELECT idpost, namepost, authorpost, datepost FROM post WHERE namepost LIKE '%$research%' ORDER BY idpost DESC";
                    $result = $conn->query($get_posts);
                    if($result->num_rows > 0){
                        while($result_row = $result->fetch_assoc()){
                            $idpost = $result_row["idpost"];
                            $questionpost = $result_row["namepost"];
                            $authorpost = $result_row["authorpost"];
                            $post_time = strtotime($result_row['datepost']);
                            print_r (
                                '       <div class="forumboxhover">
                                            <form id="accesspost'.$idpost.'" action="conv.php" method="post">
                                                <input type="text" name="idpost" value="'.$idpost.'" hidden></input>
                                                <input type="text" name="questionpost" value="'.$questionpost.'" hidden></input>
                                                <input type="text" name="authorpost" value="'.$authorpost.'" hidden></input>
                                                <input type="text" name="posteddate" value="'.date('j/m/Y | H:i', $post_time).'" hidden></input>
                                            </form>
                                            <div class="forumbox" onclick=accessform('.$idpost.')>
                                                <h3>'.$questionpost.'</h3>
                                            
                                                <span>Écris par '.$authorpost.'</span><br>
                                                <span>'.date('j/m/Y | H:i', $post_time).'</span>
                                            </div>
                                        </div>
                                '
                            );
                        };
                    }else{
                        echo("
                        <h3 class='foundnothing'>Aucun post n'a été trouvé.</h3>
                        ");
                    }

                };
            ?>
    </div>
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