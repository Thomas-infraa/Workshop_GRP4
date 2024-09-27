<?php
    session_start();    
    date_default_timezone_set('Europe/Paris');

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "ForumBDD";

    $actualdate = date('Y-m-d H:i:s');
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $author = mysqli_real_escape_string($conn, htmlspecialchars($_SESSION['username']));
    $idpost = mysqli_real_escape_string($conn, htmlspecialchars($_POST['idpost']));
    $responsepost = mysqli_real_escape_string($conn, htmlspecialchars($_POST['responsepost']));
    $sql = "INSERT INTO reponse (responsecontenet, idauthor, datepost, idpost) VALUES ('$responsepost','$author','$actualdate','$idpost')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: conv.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    };
?>