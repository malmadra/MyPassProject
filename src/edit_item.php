<?php
require_once 'utils/SessionManager.php';
require_once 'utils/db.php';

SessionManager::startSession();

if (!SessionManager::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = SessionManager::getUserId();

// Ensure an item ID was submitted
if (!isset($_POST['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_POST['id'];

// Fetch the item to edit
$stmt = $pdo->prepare("SELECT * FROM vault_items WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// If item not found or does not belong to user
if (!$item) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <h2>Edit Vault Item</h2>

    <form method="POST" action="update_item.php">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">

        <label>Type:</label>
        <input type="text" name="type" value="<?= htmlspecialchars($item['type']) ?>" required><br><br>

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required><br><br>

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($item['username']) ?>"><br><br>

        <label>Password:</label>
        <input type="text" name="password" value="<?= htmlspecialchars($item['password']) ?>"><br><br>

        <label>Notes:</label><br>
        <textarea name="notes"><?= htmlspecialchars($item['notes']) ?></textarea><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</body>
</html>
