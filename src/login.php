<?php
// Load required utilities with absolute paths
require_once __DIR__ . '/utils/SessionManager.php';
require_once __DIR__ . '/utils/db.php';

// Start the session
SessionManager::startSession();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    } else {
        // Prepare and execute query
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //BYPASS:
        // Log user in as long as email exists in the DB
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - MyPass</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <h2>Login to MyPass</h2>

    <?php foreach ($errors as $e): ?>
        <p style="color:red"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Master Password"><br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
