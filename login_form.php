
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <main>
        <h2>Login</h2>
        <?php if (isset($_SESSION['login_error'])): ?>
            <p style="color:red" id="error">
                <?php echo $_SESSION['login_error']; ?>
            </p>
            <?php
                if (isset($_SESSION['lockout_remaining'])) {
                    echo "<script>var countdownTime = {$_SESSION['lockout_remaining']};</script>";
                    unset($_SESSION['lockout_remaining']);
                }
            ?>
        <?php unset($_SESSION['login_error']); endif; ?>

        <form action="login.php" method="post">
            <label for="user_name">Username:</label>
            <input type="text" name="user_name" id="user_name" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login">
        </form>
    </main>

    <script>
    if (typeof countdownTime !== 'undefined') {
        const errorEl = document.getElementById('error');
        const interval = setInterval(() => {
            countdownTime--;
            const mins = Math.floor(countdownTime / 60);
            const secs = countdownTime % 60;
            errorEl.textContent = `Too many failed attempts. Try again in ${mins} minute(s) and ${secs} second(s).`;
            if (countdownTime <= 0) clearInterval(interval);
        }, 1000);
    }
    </script>
</body>
</html>
