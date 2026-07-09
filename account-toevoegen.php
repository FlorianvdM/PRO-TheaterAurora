<?php
// account-toevoegen.php – Nieuw account formulier (Admin)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$paginaTitel = 'Account toevoegen';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam = $_POST['voornaam'] ?? '';
    $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $email = $_POST['email'] ?? '';
    $mobiel = $_POST['mobiel'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (empty($voornaam) || empty($achternaam) || empty($gebruikersnaam) || empty($wachtwoord) || empty($email) || empty($mobiel) || empty($rol)) {
        $error = 'Niet alle gegevens correct ingevuld';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, Isactief, Datumaangemaakt, Datumgewijzigd)
                                   VALUES (:voornaam, :tussenvoegsel, :achternaam, :gebruikersnaam, :wachtwoord, 0, 1, NOW(6), NOW(6))');
            $stmt->execute([
                ':voornaam' => $voornaam,
                ':tussenvoegsel' => $tussenvoegsel ?: null,
                ':achternaam' => $achternaam,
                ':gebruikersnaam' => $gebruikersnaam,
                ':wachtwoord' => password_hash($wachtwoord, PASSWORD_DEFAULT)
            ]);
            $gebruikerId = $pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO Contact (GebruikerId, Email, Mobiel, Isactief, Datumaangemaakt, Datumgewijzigd)
                                   VALUES (:gebruikerId, :email, :mobiel, 1, NOW(6), NOW(6))');
            $stmt->execute([
                ':gebruikerId' => $gebruikerId,
                ':email' => $email,
                ':mobiel' => $mobiel
            ]);

            $stmt = $pdo->prepare('INSERT INTO Rol (GebruikerId, Naam, Isactief, Datumaangemaakt, Datumgewijzigd)
                                   VALUES (:gebruikerId, :rol, 1, NOW(6), NOW(6))');
            $stmt->execute([
                ':gebruikerId' => $gebruikerId,
                ':rol' => $rol
            ]);

            $pdo->commit();
            header('Location: accounts.php?succes=1');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Niet alle gegevens correct ingevuld';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Nieuw account</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="" class="form-container">
            <div class="form-group">
                <label for="voornaam">Voornaam *</label>
                <input type="text" id="voornaam" name="voornaam" required value="<?php echo htmlspecialchars($_POST['voornaam'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="tussenvoegsel">Tussenvoegsel</label>
                <input type="text" id="tussenvoegsel" name="tussenvoegsel" value="<?php echo htmlspecialchars($_POST['tussenvoegsel'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="achternaam">Achternaam *</label>
                <input type="text" id="achternaam" name="achternaam" required value="<?php echo htmlspecialchars($_POST['achternaam'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="gebruikersnaam">Gebruikersnaam *</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" required value="<?php echo htmlspecialchars($_POST['gebruikersnaam'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="wachtwoord">Wachtwoord *</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="mobiel">Mobiel *</label>
                <input type="text" id="mobiel" name="mobiel" required value="<?php echo htmlspecialchars($_POST['mobiel'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="rol">Rol *</label>
                <select id="rol" name="rol" required>
                    <option value="">Kies rol...</option>
                    <option value="Admin" <?php echo ($_POST['rol'] ?? '') === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="Medewerker" <?php echo ($_POST['rol'] ?? '') === 'Medewerker' ? 'selected' : ''; ?>>Medewerker</option>
                    <option value="Bezoeker" <?php echo ($_POST['rol'] ?? '') === 'Bezoeker' ? 'selected' : ''; ?>>Bezoeker</option>
                </select>
            </div>
            <button type="submit" class="knop knop-primair">Account aanmaken</button>
            <a href="accounts.php" class="knop knop-secundair">Annuleren</a>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
