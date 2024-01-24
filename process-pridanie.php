<?php
session_start();

if (isset($_SESSION["id"])) {
    $mysqli = require __DIR__ . "/connect.php";
    
    $sql = "SELECT * FROM user WHERE user_id = {$_SESSION["id"]}";
    $resultUser = $mysqli->query($sql);

    // Fetch user details
    $userDetails = $resultUser->fetch_assoc();
    $user_id = $userDetails['user_id'];
} else {
    echo "Najskor sa prihlas!!!";
}

if (isset($_POST['pridat'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $skola = $_POST['skola'];
    $content = $_POST['content'];

    if ($category_id == "" || $user_id == "" || $title == "" || $skola == "" || $content == "") {
        $err = true;
    } else if ($category_id == "-1") {
        $errReferat = true;
    } else {
        // Prepare and bind the SQL statement
        $sql = "INSERT INTO referaty (user_id, category_id, title, skola, content) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iisss", $user_id, $category_id, $title, $skola, $content);
        
        // Execute the statement
        $isInserted = $stmt->execute();

        if ($isInserted) {
            echo "Referat added successfully.";
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
        }

        // Close the statement
        $stmt->close();
    }
}
?>
