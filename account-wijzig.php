<?php
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$paginaTitel = 'Wijzig account';
$error = null;
$succes = false;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($pdo === null) {
    $error = 'Database niet verbonden';
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $voornaam = $_POST['voornaam'] ?? '';
        $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
        $achternaam = $_POST['achternaam'] ?? '';
        $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
        $wachtwoord = $_POST['wachtwoord'] ?? '';
        $email = $_POST['email'] ?? '';
        $mobiel = $_POST['mobiel'] ?? '';
        $rol = $_POST['rol'] ?? '';

        if (empty($voornaam) || empty($achternaam) || empty($gebruikersnaam) || empty($email) || empty($mobiel) || empty($rol)) {
            $error = 'Niet alle gegevens correct ingevuld';
        } else {
            try {
                $pdo->beginTransaction();

                if ($wachtwoord !== '') {
                    $stmt = $pdo->prepare('UPDATE Gebruiker SET Voornaam = :voornaam, Tussenvoegsel = :tussenvoegsel, Achternaam = :achternaam, Gebruikersnaam = :gebruikersnaam, Wachtwoord = :wachtwoord, Datumgewijzigd = NOW(6) WHERE Id = :id');
                    $stmt->execute([
                        ':voornaam' => $voornaam,
                        ':tussenvoegsel' => $tussenvoegsel ?: null,
                        ':achternaam' => $achternaam,
                        ':gebruikersnaam' => $gebruikersnaam,
                        ':wachtwoord' => password_hash($wachtwoord, PASSWORD_DEFAULT),
                        ':id' => $id
                    ]);
                } else {
                    $stmt = $pdo->prepare('UPDATE Gebruiker SET Voornaam = :voornaam, Tussenvoegsel = :tussenvoegsel, Achternaam = :achternaam, Gebruikersnaam = :gebruikersnaam, Datumgewijzigd = NOW(6) WHERE Id = :id');
                    $stmt->execute([
                        ':voornaam' => $voornaam,
                        ':tussenvoegsel' => $tussenvoegsel ?: null,
                        ':achternaam' => $achternaam,
                        ':gebruikersnaam' => $gebruikersnaam,
                        ':id' => $id
                    ]);
                }

                $stmt = $pdo->prepare('UPDATE Contact SET Email = :email, Mobiel = :mobiel, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id');
                $stmt->execute([
                    ':email' => $email,
                    ':mobiel' => $mobiel,
                    ':id' => $id
                ]);

                $stmt = $pdo->prepare('UPDATE Rol SET Naam = :rol, Datumgewijzigd = NOW(6) WHERE GebruikerId = :id AND Isactief = 1');
                $stmt->execute([
                    ':rol' => $rol,
                    ':id' => $id
                ]);

                $pdo->commit();
                header('Location: accounts.php?succes=2');
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = 'Database niet verbonden';
            }
        }
    }

    $stmt = $pdo->prepare('SELECT g.Id, g.Voornaam, g.Tussenvoegsel, g.Achternaam, g.Gebruikersnaam, c.Email, c.Mobiel, r.Naam AS Rol
                           FROM Gebruiker g
                           LEFT JOIN Contact c ON c.GebruikerId = g.Id
                           LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.Isactief = 1
                           WHERE g.Id = :id AND g.Isactief = 1');
    $stmt->execute([':id' => $id]);
    $account = $stmt->fetch();

    if (!$account) {
        $error = 'Account niet gevonden';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Account wijzigen</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($account) && $account): ?>
        <form method="post" action="account-wijzig.php?id=<?= (int)$id ?>" class="form-container">
            <div class="form-group">
                <label for="voornaam">Voornaam *</label>
                <input type="text" id="voornaam" name="voornaam" required value="<?= htmlspecialchars($_POST['voornaam'] ?? $account['Voornaam']) ?>">
            </div>
            <div class="form-group">
                <label for="tussenvoegsel">Tussenvoegsel</label>
                <input type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?= htmlspecialchars($_POST['tussenvoegsel'] ?? $account['Tussenvoegsel'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="achternaam">Achternaam *</label>
                <input type="text" id="achternaam" name="achternaam" required value="<?= htmlspecialchars($_POST['achternaam'] ?? $account['Achternaam']) ?>">
            </div>
            <div class="form-group">
                <label for="gebruikersnaam">Gebruikersnaam *</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" required value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? $account['Gebruikersnaam']) ?>">
            </div>
            <div class="form-group">
                <label for="wachtwoord">Nieuw wachtwoord (laat leeg om te behouden)</label>
                <input type="password" id="wachtwoord" name="wachtwoord">
            </div>
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? $account['Email']) ?>">
            </div>
            <div class="form-group">
                <label for="mobiel">Mobiel *</label>
                <input type="text" id="mobiel" name="mobiel" required value="<?= htmlspecialchars($_POST['mobiel'] ?? $account['Mobiel']) ?>">
            </div>
            <div class="form-group">
                <label for="rol">Rol *</label>
                <select id="rol" name="rol" required>
                    <option value="">Kies rol...</option>
                    <option value="Admin" <?= ($_POST['rol'] ?? $account['Rol']) === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Medewerker" <?= ($_POST['rol'] ?? $account['Rol']) === 'Medewerker' ? 'selected' : '' ?>>Medewerker</option>
                    <option value="Bezoeker" <?= ($_POST['rol'] ?? $account['Rol']) === 'Bezoeker' ? 'selected' : '' ?>>Bezoeker</option>
                </select>
            </div>
            <button type="submit" class="knop knop-primair">Wijzigingen opslaan</button>
            <a href="accounts.php" class="knop knop-secundair">Annuleren</a>
        </form>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>