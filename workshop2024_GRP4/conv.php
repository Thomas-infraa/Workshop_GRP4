<?php
    session_start();    
    date_default_timezone_set('Europe/Paris');
    // if(!isset($_POST['authorpost'])){
    //     header('Location: forum.php');
    //     exit;
    // };
    function lookforuser(){
        if(isset($_POST['idpost']) && !isset($_SESSION["lastpostseen"])){
            $_SESSION["lastpostseen"] = $_POST['idpost'];
            $_SESSION["lastquestionpost"] = $_POST['questionpost'];
            $_SESSION["lastauthorpost"] = $_POST['authorpost'];
            $_SESSION["lastposteddate"] = $_POST['posteddate'];
        }else if(!isset($_POST['idpost']) && isset($_SESSION["lastpostseen"])){
            throw new \Exception('');
        }else{
            header('Location: forum.php');
            exit;
        };
    };

    try {
        lookforuser();
    }catch (\Exception $e){
        echo'';
    };

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleForum.css">
    <script type="text/javascript" src="main.js"></script>
    <title>Question de <?php echo($_SESSION["lastauthorpost"].' | '.$_SESSION["lastquestionpost"])?></title>
</head>
<body>
    <header>
        <div class="header-content">
            <a class="btn-menu" href='forum.php' >Retour Forum</a>
            <a href='principale.php?deconnexion=true' class="btn-logout">Déconnecter</a>
        </div>
    </header>
    <div>
    <h1>Post de <?php echo($_SESSION["lastauthorpost"])?></h1>
    </div>
    <div>
        <div class="forumbox firstquestion">
            <strong>
            <?php echo($_SESSION["lastquestionpost"])?>
            </strong>
        </div>
        <div class="dateconv"><?php echo($_SESSION["lastposteddate"])?></div>
    </div>
    <div class="responsesdiv">
        <button id="newresponsebutton" class="btn-menu" style="margin-left: 1rem;" onclick='clicknewresponse()'>Répondre</button>
        <div class="newpostbox" id="newresponsebox" style="display: none;">
            <form action="response.php" method="post">
                <label><strong>Répondre :</strong></label>
                <input type="textarea" name="responsepost"></input>
                <input type="text" name="idpost" value="<?php echo(htmlspecialchars($_SESSION["lastpostseen"]))?>" hidden>
                <button type="submit">Poster</button>
            </form>
        </div>
        <h2>Réponses :</h2>
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
        };
        $idpost = mysqli_real_escape_string($conn, htmlspecialchars($_SESSION["lastpostseen"]));
        $get_responses = "SELECT idpost, responsecontenet, idauthor, datepost FROM reponse WHERE idpost = '$idpost' ORDER BY datepost ASC";
        $result = $conn->query($get_responses);
        if($result->num_rows > 0){
            while($result_row = $result->fetch_assoc()){
                $responsecontent = $result_row["responsecontenet"];
                $authorresponse = $result_row["idauthor"];
                $post_time = strtotime($result_row['datepost']);
                print_r ('
                        <form class="responsebox" action="conv.php" method="post">
                            <div class="">
                                <h3>'.$authorresponse.'</h3>
                            </div>
                            <div class="forumbox">
                                <span>'.$responsecontent.'</span>
                            </div>
                            <span class="">'.date('j/m/Y | H:i', $post_time)  .'.</span>
                        </form>
                ');
            };
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