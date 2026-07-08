<?php
// login.php – Inlogformulier met wachtwoord verificatie
session_start();
require_once __DIR__ . '/includes/db.php';

$paginaTitel = 'Inloggen – TheaterAurora';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Formulier ingediend
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if (empty($gebruikersnaam) || empty($wachtwoord)) { // Validatie
        $error = 'Vul alle velden in.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        $stmt = $pdo->prepare('SELECT Id, Gebruikersnaam, Wachtwoord, Isactief FROM Gebruiker WHERE Gebruikersnaam = :gebruikersnaam');
        $stmt->execute([':gebruikersnaam' => $gebruikersnaam]);
        $gebruiker = $stmt->fetch();

        if ($gebruiker && password_verify($wachtwoord, $gebruiker['Wachtwoord'])) { // Inloggen gelukt
            if (!$gebruiker['Isactief']) {
                $error = 'Account is gedeactiveerd.';
            } else { // Sessie aanmaken
                $stmt = $pdo->prepare('UPDATE Gebruiker SET IsIngelogd = 1, Ingelogd = NOW() WHERE Id = :id');
                $stmt->execute([':id' => $gebruiker['Id']]);

                $stmt = $pdo->prepare('SELECT Naam FROM Rol WHERE GebruikerId = :id AND Isactief = 1');
                $stmt->execute([':id' => $gebruiker['Id']]);
                $rol = $stmt->fetchColumn();

                $_SESSION['gebruiker_id'] = $gebruiker['Id'];
                $_SESSION['gebruikersnaam'] = $gebruiker['Gebruikersnaam'];
                $_SESSION['rol'] = $rol ?: 'Bezoeker';

                header('Location: index.php');
                exit;
            }
        } else {
            $error = 'Gebruikersnaam of wachtwoord is onjuist.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Inloggen</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="" class="form-container">
            <div class="form-group">
                <label for="gebruikersnaam">Gebruikersnaam</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" required value="<?php echo htmlspecialchars($_POST['gebruikersnaam'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required>
            </div>
            <button type="submit" class="knop knop-primair">Inloggen</button>
        </form>
        <p style="margin-top: 16px; text-align: center; font-size: 13px; color: var(--kleur-tekst-zacht);">
          <a href="demo-login.php" style="color: var(--kleur-accent);">Demo: direct inloggen met een rol</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
