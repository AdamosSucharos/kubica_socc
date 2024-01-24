<?php
session_start();

$mysqli = require __DIR__ . "/connect.php";

$categories = [];
$subcategories = [];
// Check if referat_id is set in the URL
if (isset($_GET['referat_id'])) {
    $referat_id = $_GET['referat_id'];

    // Fetch paper details from the database
    $sql = "SELECT referaty.*, category.category_name 
            FROM referaty 
            JOIN category ON referaty.category_id = category.category_id 
            WHERE referaty.referat_id = $referat_id";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $paperDetails = $result->fetch_assoc();
        // You can now use $paperDetails to display the paper details on the page
    } else {
        // Handle the case where the paper with the given referat_id is not found
        echo "Paper not found.";
    }
} else {
    // Handle the case where referat_id is not set in the URL
    echo "Invalid request.";
}

$result = $mysqli->query("SELECT * FROM category WHERE parent_category_id IS NULL");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch subcategories
$result = $mysqli->query("SELECT * FROM category WHERE parent_category_id IS NOT NULL");
while ($row = $result->fetch_assoc()) {
    $subcategories[$row['parent_category_id']][] = $row;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view_paper.css?rand<?php echo rand(1, 90); ?>">
    <title><?php echo $paperDetails['title'];?> </title>

</head>
<body>

    
<?php require_once("header.php"); ?>

    <div class="container">
        <h1><?php echo $paperDetails['title']; ?>
        <?php if (isset($user) && $user['user_id'] == $paperDetails['user_id']): ?>
            <a href="add_test.php?referat_id=<?php echo $referat_id; ?>" class="add-test-button">Pridaj test</a>
        <?php endif; ?></h1>
        <p>Kategória: <?php echo $paperDetails['category_name']; ?></p>
        <p><?php echo $paperDetails['content']; ?></p>

        
    </div>
    <?php require_once("footer.php");?>
</body>
</html>
