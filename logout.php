<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (isset($_SESSION['gebruiker_id']) && $pdo !== null) {
    $stmt = $pdo->prepare('UPDATE Gebruiker SET IsIngelogd = 0, Uitgelogd = NOW() WHERE Id = :id');
    $stmt->execute([':id' => $_SESSION['gebruiker_id']]);
}

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
