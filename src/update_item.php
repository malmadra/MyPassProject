<?php
require_once 'utils/SessionManager.php';
require_once 'utils/db.php';

SessionManager::startSession();

if (!SessionManager::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = SessionManager::getUserId();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $type = trim($_POST['type']);
    $title = trim($_POST['title']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $notes = trim($_POST['notes']);

    // Update only the item that belongs to this user
    $stmt = $pdo->prepare("UPDATE vault_items 
                           SET type = ?, title = ?, username = ?, password = ?, notes = ?
                           WHERE id = ? AND user_id = ?");
    $stmt->execute([$type, $title, $username, $password, $notes, $id, $userId]);

    header("Location: dashboard.php");
    exit;
}

// Fallback redirect
header("Location: dashboard.php");
exit;
?>
