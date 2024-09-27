<?php
    session_start();    
    date_default_timezone_set('Europe/Paris');

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "ForumBDD";
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $author = mysqli_real_escape_string($conn, htmlspecialchars($_POST['user']));
    $askpost = mysqli_real_escape_string($conn, htmlspecialchars($_POST["askpost"]));
    $actualdate = mysqli_real_escape_string($conn, date("Y-m-d H:i:s"));
    $sql = "INSERT INTO post (namepost, authorpost, datepost) VALUES ('$askpost','$author','$actualdate')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: forum.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    };
?>