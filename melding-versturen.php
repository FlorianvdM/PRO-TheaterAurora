<?php
// melding-versturen.php – Melding versturen naar bezoekers (Admin, Medewerker)
// De melding wordt gemarkeerd als "Verzonden"; bezoekers zien 'm daarna als banner
// zodra ze ingelogd zijn (zie includes/header.php).
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$id = $_GET['id'] ?? '';

if ($id === '' || $pdo === null) { // Geen id opgegeven of geen databaseverbinding
    header('Location: meldingen.php');
    exit;
}

// Melding ophalen
$stmt = $pdo->prepare('SELECT Id FROM Melding WHERE Id = :id AND Isactief = 1');
$stmt->execute([':id' => $id]);
$melding = $stmt->fetch();

if (!$melding) { // Melding bestaat niet (meer)
    header('Location: meldingen.php');
    exit;
}

try {
    // Melding markeren als verzonden, zodat bezoekers 'm als banner te zien krijgen
    $stmt = $pdo->prepare('
        UPDATE Melding
        SET Verzonden = 1,
            VerzondenOp = NOW(6),
            Datumgewijzigd = NOW(6)
        WHERE Id = :id
    ');
    $stmt->execute([':id' => $id]);
} catch (PDOException $e) {
    header('Location: meldingen.php');
    exit;
}

// Aantal actieve bezoekers ophalen, puur voor de feedbackmelding aan de medewerker
$aantalBezoekers = 0;
try {
    $stmt = $pdo->query('SELECT COUNT(*) FROM Bezoeker WHERE Isactief = 1');
    $aantalBezoekers = (int) $stmt->fetchColumn();
} catch (PDOException $e) {
    $aantalBezoekers = 0;
}

header('Location: meldingen.php?verzonden=1&aantal=' . $aantalBezoekers);
exit;