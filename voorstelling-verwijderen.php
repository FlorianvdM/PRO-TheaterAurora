<?php
// voorstelling-verwijderen.php – Voorstelling verwijderen (Admin, soft delete)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$id = $_GET['id'] ?? '';

if ($id === '' || $pdo === null) { // Geen id opgegeven of geen databaseverbinding
    header('Location: voorstellingen.php');
    exit;
}

$stmt = $pdo->prepare('SELECT Id FROM Voorstelling WHERE Id = :id AND Isactief = 1');
$stmt->execute([':id' => $id]);
$voorstelling = $stmt->fetch();

if (!$voorstelling) { // Voorstelling bestaat niet (meer)
    header('Location: voorstellingen.php');
    exit;
}

try { // Soft delete: Isactief op 0 zetten, record blijft bewaard
    $stmt = $pdo->prepare('
        UPDATE Voorstelling
        SET Isactief = 0,
            Datumgewijzigd = NOW(6)
        WHERE Id = :id
    ');
    $stmt->execute([':id' => $id]);
} catch (PDOException $e) {
    header('Location: voorstellingen.php');
    exit;
}

header('Location: voorstellingen.php?verwijderd=1');
exit;