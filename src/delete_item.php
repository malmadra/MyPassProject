<?php
require_once 'utils/SessionManager.php';
require_once 'utils/db.php';

SessionManager::startSession();

// Must be logged in
if (!SessionManager::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = SessionManager::getUserId();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];

    // Delete item only if it belongs to the logged-in user
    $stmt = $pdo->prepare("DELETE FROM vault_items WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);

    header("Location: dashboard.php");
    exit;
}

// Fallback redirect
header("Location: dashboard.php");
exit;
?>
