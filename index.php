<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Math Solver</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="card">
            <h2>Login</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="auth/login.php" method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log In</button>
            </form>
            <p>New here? <a class="link" href="signup.php">Create an account</a></p>
        </div>
    </div>

</body>
</html>
