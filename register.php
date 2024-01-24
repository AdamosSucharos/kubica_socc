<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="register.css?rand<?php echo rand (1,90);?>">
    <title>Document</title>
</head>
<body>



<div class="container">
        <form action="process-signup.php" method="post" id="signup" novalidate>
            <h2>Register</h2>

            <?php if (!empty($err)): ?>
                <em>Invalid login</em>
            <?php endif; ?>

            <label for="username">Prezývka:</label>
            <input type="text" name="name" id= "name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id= "email" required>

            <label for="password">Heslo:</label>
            <input type="password" name="password" id= "password" required>

            <label for="password_confirmation">Potvrď heslo:</label>
            <input type="password" name="password_confirmation" id= "password_confirmation" required>


            <button type="submit" name= "submit">Register</button>
        </form>
    </div>

</body>
</html>