<?php
require_once 'utils/SessionManager.php';
require_once 'utils/db.php';

// Load Observer pattern
require_once 'utils/patterns/observer/Subject.php';
require_once 'utils/patterns/observer/Observer.php';
require_once 'utils/patterns/observer/VaultSubject.php';
require_once 'utils/patterns/observer/WeakPasswordObserver.php';
require_once 'utils/patterns/observer/ExpirationObserver.php';
SessionManager::startSession();

if (!SessionManager::isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = SessionManager::getUserId();
$errors = [];
$observerMessages = [];
// Load Proxy pattern
require_once __DIR__ . '/utils/patterns/Proxy/VaultAccessProxy.php';
require_once __DIR__ . '/utils/patterns/Proxy/VaultInterface.php';

use Utils\Patterns\Proxy\VaultAccessProxy;

$proxy = new VaultAccessProxy("admin");
$proxyMessage = $proxy->getItem(5);

// MEDIATOR PATTERN 
require_once __DIR__ . '/utils/patterns/mediator/Mediator.php';
require_once __DIR__ . '/utils/patterns/mediator/FormComponent.php';
require_once __DIR__ . '/utils/patterns/mediator/TitleComponent.php';
require_once __DIR__ . '/utils/patterns/mediator/PasswordComponent.php';

use Utils\Patterns\Mediator\Mediator;
use Utils\Patterns\Mediator\TitleComponent;
use Utils\Patterns\Mediator\PasswordComponent;

$mediator = new Mediator();
$mediator->register("title", new TitleComponent());
$mediator->register("password", new PasswordComponent());

// CHAIN OF RESPONSIBILITY
require_once __DIR__ . '/utils/patterns/chain/BaseHandler.php';
require_once __DIR__ . '/utils/patterns/chain/LengthHandler.php';
require_once __DIR__ . '/utils/patterns/chain/NumberHandler.php';
require_once __DIR__ . '/utils/patterns/chain/UppercaseHandler.php';
require_once __DIR__ . '/utils/patterns/chain/SymbolHandler.php';

use Utils\Patterns\Chain\LengthHandler;
use Utils\Patterns\Chain\NumberHandler;
use Utils\Patterns\Chain\UppercaseHandler;
use Utils\Patterns\Chain\SymbolHandler;

// Create chain
$length = new LengthHandler();
$number = new NumberHandler();
$upper  = new UppercaseHandler();
$symbol = new SymbolHandler();

// Build chain order
$length->setNext($number)->setNext($upper)->setNext($symbol);


// Handle form submission to save vault item
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = trim($_POST['type']);
    $title = trim($_POST['title']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $notes = trim($_POST['notes']);
    // Run Mediator validation
    $mediatorErrors = $mediator->validate([
        "title" => $title,
        "password" => $password
    ]);

    if (!empty($mediatorErrors)) {
        $errors = array_merge($errors, $mediatorErrors);
    }
     // CHAIN OF RESPONSIBILITY VALIDATION
    $chainError = $length->validate($password);
    if ($chainError) {
        $errors[] = $chainError;
    }
    if (empty($type) || empty($title)) {
        $errors[] = "Type and Title are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO vault_items (user_id, type, title, username, password, notes)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $type, $title, $username, $password, $notes]);

        // Observer Pattern: set up subject and observers
        ob_start(); // Start output buffering

        $subject = new \Utils\Patterns\Observer\VaultSubject();
        $subject->attach(new \Utils\Patterns\Observer\WeakPasswordObserver());
        $subject->attach(new \Utils\Patterns\Observer\ExpirationObserver());

        // Notify password observer
        $subject->notify([
            'type' => 'password',
            'value' => $password
        ]);

        // Notify expiration observer (mock data, replace later with real expiring items)
        $subject->notify([
            'type' => 'expiration',
            'label' => 'Sample Card',
            'date' => '2025-12-10' // replace with actual expiration date field if needed
        ]);

        $observerMessages[] = ob_get_clean(); // Capture output
    }
}

// Fetch all saved items
$stmt = $pdo->prepare("SELECT * FROM vault_items WHERE user_id = ?");
$stmt->execute([$userId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - MyPass</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <h2>Welcome to Your MyPass Dashboard</h2>
    <p><strong>Proxy Output:</strong> <?= htmlspecialchars($proxyMessage) ?></p>
    <p>You are logged in as: <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>
    <p><a href="logout.php">Logout</a></p>

    <?php foreach ($errors as $e): ?>
        <p style="color:red"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <?php foreach ($observerMessages as $msg): ?>
        <div><?= $msg ?></div>
    <?php endforeach; ?>

    <h3>Add New Vault Item</h3>

    <form method="POST">
        <input type="text" name="type" placeholder="Type (login, note, card)" required><br><br>
        <input type="text" name="title" placeholder="Title" ><br><br>
        <input type="text" name="username" placeholder="Username"><br><br>

        <!-- Password Input -->
        <label>Password</label><br>
        <input type="text" id="password" name="password" placeholder="Password"><br><br>

        <!-- Password Generator -->
        <h4>Generate a Secure Password</h4>
        <label>Password Length: <span id="lengthDisplay">12</span></label>
        <input type="range" id="length" min="8" max="32" value="12"><br><br>

        <input type="checkbox" id="includeUpper" checked> Include Uppercase<br>
        <input type="checkbox" id="includeLower" checked> Include Lowercase<br>
        <input type="checkbox" id="includeNumbers" checked> Include Numbers<br>
        <input type="checkbox" id="includeSymbols"> Include Symbols<br><br>

        <button type="button" onclick="generatePassword()">Generate Password</button><br><br>

        <textarea name="notes" placeholder="Notes (optional)"></textarea><br><br>
        <button type="submit">Save Item</button>
    </form>

    <h3>Your Vault Items</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Type</th>
            <th>Title</th>
            <th>Username</th>
            <th>Password</th>
            <th>Notes</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['type']) ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['username']) ?></td>

                <td>
                    <input type="password"
                           value="<?= htmlspecialchars($item['password']) ?>"
                           readonly
                           style="border:none; background:none;"
                           id="pw<?= $item['id'] ?>">
                    <button type="button" onclick="togglePassword('pw<?= $item['id'] ?>')">Show</button>
                </td>

                <td><?= htmlspecialchars($item['notes']) ?></td>
                <td><?= $item['created_at'] ?></td>

                <td>
                    <form method="POST" action="edit_item.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit">Edit</button>
                    </form>

                    <form method="POST" action="delete_item.php" style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }

    document.getElementById("length").addEventListener("input", function () {
        document.getElementById("lengthDisplay").textContent = this.value;
    });

    function generatePassword() {
        const length = document.getElementById("length").value;
        const includeUpper = document.getElementById("includeUpper").checked ? 1 : 0;
        const includeLower = document.getElementById("includeLower").checked ? 1 : 0;
        const includeNumbers = document.getElementById("includeNumbers").checked ? 1 : 0;
        const includeSymbols = document.getElementById("includeSymbols").checked ? 1 : 0;

        fetch(`generate_password.php?length=${length}&upper=${includeUpper}&lower=${includeLower}&numbers=${includeNumbers}&symbols=${includeSymbols}`)
            .then(response => response.text())
            .then(password => {
                document.getElementById("password").value = password;
            });
    }
    </script>
</body>
</html>
