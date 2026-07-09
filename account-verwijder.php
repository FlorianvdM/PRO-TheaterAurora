<?php
// account-verwijder.php – Account verwijderen (Admin, soft delete)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$id = $_GET['id'] ?? '';

if ($id === '' || $pdo === null) {
    header('Location: accounts.php');
    exit;
}

if ((int) $id === (int) ($_SESSION['gebruiker_id'] ?? 0)) {
    header('Location: accounts.php');
    exit;
}

$stmt = $pdo->prepare('SELECT Id FROM Gebruiker WHERE Id = :id AND Isactief = 1');
$stmt->execute([':id' => $id]);
$gebruiker = $stmt->fetch();

if (!$gebruiker) {
    header('Location: accounts.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('UPDATE Gebruiker SET Isactief = 0, Datumgewijzigd = NOW(6) WHERE Id = :id');
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare('UPDATE Contact SET Isactief = 0, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id');
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare('UPDATE Rol SET Isactief = 0, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id');
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare('UPDATE Medewerker SET Isactief = 0, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id');
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare('UPDATE Bezoeker SET Isactief = 0, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id');
    $stmt->execute([':id' => $id]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: accounts.php');
    exit;
}

header('Location: accounts.php?verwijderd=1');
exit;
