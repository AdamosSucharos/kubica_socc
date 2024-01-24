<?php
$err;
if (empty($_POST["name"])) {
    $err=("Name is required");
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $err=("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    $err=("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    $err=("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    $err=("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    $err=("Passwords must match");
}


$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

require_once("connect.php");

// Check if the email already exists in the database
$checkSql = "SELECT email FROM user WHERE email = ?";
$checkStmt = $mysqli->stmt_init();

if (!$checkStmt->prepare($checkSql)) {
    die("SQL error: " . $mysqli->error);
}

$checkStmt->bind_param("s", $_POST["email"]);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    die("Email already taken");
}

// If the email is not already in the database, proceed with the insertion
$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();  // Define $stmt here

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

if ($stmt->execute()) {
    header("Location: login.php");
    exit;
} else {
    die($mysqli->error . " " . $mysqli->errno);
}