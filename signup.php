<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Math Solver</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="math-bg"></div>

    <div class="container">
        <div class="card">

            <h2><i class="fas fa-user-plus"></i> Sign Up</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="auth/register.php" method="POST">
                <input type="text" name="name" placeholder="📛 Full Name" required>
                <input type="email" name="email" placeholder="📩 Email Address" required>
                <input type="password" name="password" placeholder="🔑 Password" required>
                <button type="submit">🚀 Sign Up</button>
                
                <p>Already have an account? <a class="link" href="index.php">Login</a></p>
            </form>
        </div>
    </div>

</body>

</html>