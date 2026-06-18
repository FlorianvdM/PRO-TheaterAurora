<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$paginaTitel = 'Inloggen – TheaterAurora';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if (empty($gebruikersnaam) || empty($wachtwoord)) {
        $error = 'Vul alle velden in.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        $stmt = $pdo->prepare('SELECT Id, Gebruikersnaam, Wachtwoord, Isactief FROM Gebruiker WHERE Gebruikersnaam = :gebruikersnaam');
        $stmt->execute([':gebruikersnaam' => $gebruikersnaam]);
        $gebruiker = $stmt->fetch();

        if ($gebruiker && password_verify($wachtwoord, $gebruiker['Wachtwoord'])) {
            if (!$gebruiker['Isactief']) {
                $error = 'Account is gedeactiveerd.';
            } else {
                $stmt = $pdo->prepare('UPDATE Gebruiker SET IsIngelogd = 1, Ingelogd = NOW() WHERE Id = :id');
                $stmt->execute([':id' => $gebruiker['Id']]);

                $_SESSION['gebruiker_id'] = $gebruiker['Id'];
                $_SESSION['gebruikersnaam'] = $gebruiker['Gebruikersnaam'];

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
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
