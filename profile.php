<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$mysqli = require __DIR__ . "/connect.php";
$userId = $_SESSION['id'];

// Fetch user information from the database
$userSql = sprintf("SELECT * FROM user WHERE user_id = %d", $userId);
$userResult = $mysqli->query($userSql);
$user = $userResult->fetch_assoc();

// Handle paper deletion if the delete button is clicked
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_paper"])) {
    $paperIdToDelete = $_POST["delete_paper"];
    $deletePaperSql = sprintf("DELETE FROM referaty WHERE referat_id = %d AND user_id = %d", $paperIdToDelete, $userId);
    $mysqli->query($deletePaperSql);
    // Redirect to refresh the page after deletion
    header("Location: profile.php");
    exit;
}

$referatyData = [];
$userId = $_SESSION['id'];

$result = $mysqli->query("SELECT referaty.referat_id, referaty.title, category.category_name, referaty.created_at, referaty.skola, user.name  
                          FROM referaty 
                          JOIN category ON referaty.category_id = category.category_id 
                          JOIN user ON referaty.user_id = user.user_id
                          WHERE referaty.user_id = $userId");

while ($row = $result->fetch_assoc()) {
    $referatyData[] = $row;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?rand<?php echo rand(1, 90); ?>";>

    <title>User Profile</title>
    <!-- Add any additional styles or scripts as needed -->
</head>
<body>
<?php require_once("header.php"); ?>
<h2>Vitaj, <?php echo $user['name']; ?>!</h2>


<table >
    <thead>
        <tr>
            <th>Názov</th>
            <th>Kategória</th>
            <th>Dátum vydania</th>
            <th>Škola</th>
        </tr>
    </thead>
    <tbody>
            <?php foreach ($referatyData as $row): ?>
                <tr>
                    <td><a href="view_paper.php?referat_id=<?php echo $row['referat_id']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php echo $row['skola']; ?></td>
                    <td>
                    <form method="post">
                        <button type="submit" name="delete_paper" value="<?php echo $row['referat_id']; ?>">Delete</button>
                    </form>
                    </td>
                </tr>
            <?php endforeach; ?>
    </tbody>
</table>

<!-- Add any additional content or features as needed -->

</body>
</html>
<?php

