<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Check if both email and password are provided
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    
        $mysqli = require __DIR__ . "/connect.php";
        
        $sql = sprintf("SELECT * FROM user
                        WHERE email = '%s'",
                       $mysqli->real_escape_string($_POST["email"]));
        
        $result = $mysqli->query($sql);
        
        $user = $result->fetch_assoc();
        
        if ($user) {
            
            if (password_verify($_POST["password"], $user["password_hash"])) {
                
                session_start();
                
                session_regenerate_id();
                
                $_SESSION["id"] = $user["user_id"];
                
                header("Location: home.php");
                exit;
            }
            $is_invalid = true;

        } else {
            $is_invalid = true;
        }
        
    } else {
        // Both email and password are required
        $is_invalid = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login.css?rand<?php echo rand (1,90);?>">
</head>
<body>

<div class="container">

    <h2>Login</h2>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <button>Log in</button>
    </form>
</div>
</body>
</html>