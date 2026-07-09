<?php
// medewerker-verwijderen.php – Medewerker soft-delete (Admin)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$paginaTitel = 'Medewerker verwijderen';
$error = null;
$medewerker = null;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($pdo === null) {
    header('Location: medewerker.php');
    exit;
}

// SELECT: haal medewerker op ter bevestiging
$stmt = $pdo->prepare('SELECT g.Id, g.Voornaam, g.Tussenvoegsel, g.Achternaam, m.Medewerkersoort, m.Nummer
                       FROM Medewerker m
                       INNER JOIN Gebruiker g ON g.Id = m.GebruikerId
                       WHERE m.GebruikerId = :id AND m.Isactief = 1');
$stmt->execute([':id' => $id]);
$medewerker = $stmt->fetch();

if (!$medewerker) {
    header('Location: medewerker.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bevestigd = $_POST['bevestigd'] ?? '';

    if ($bevestigd !== 'ja') {
        header('Location: medewerker.php');
        exit;
    }

    // Soft-delete: 5 tabellen op Isactief = 0
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
        header('Location: medewerker.php?verwijderd=1');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = 'Database niet verbonden';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Medewerker verwijderen</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($medewerker): ?>
            <p>Weet u zeker dat u de volgende medewerker wilt verwijderen?</p>
            <table class="verwijder-overzicht">
                <tr><td><strong>Naam:</strong></td><td><?= htmlspecialchars($medewerker['Voornaam'] . ' ' . ($medewerker['Tussenvoegsel'] ? $medewerker['Tussenvoegsel'] . ' ' : '') . $medewerker['Achternaam']) ?></td></tr>
                <tr><td><strong>Nummer:</strong></td><td><?= htmlspecialchars($medewerker['Nummer']) ?></td></tr>
                <tr><td><strong>Soort:</strong></td><td><?= htmlspecialchars($medewerker['Medewerkersoort']) ?></td></tr>
            </table>
            <form method="post" action="medewerker-verwijderen.php?id=<?= (int)$id ?>" style="margin-top: 20px;">
                <input type="hidden" name="bevestigd" value="ja">
                <button type="submit" class="knop knop-gevaar">Verwijderen</button>
                <a href="medewerker.php" class="knop knop-secundair">Annuleren</a>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>