<?php
// melding-toevoegen.php – Nieuw melding formulier (Admin/Medewerker)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Nieuwe melding';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $bericht = $_POST['bericht'] ?? '';
    $opmerking = $_POST['opmerking'] ?? '';

    if (empty($type) || empty($bericht)) {
        $error = 'Vul alle verplichte velden in.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            $stmt = $pdo->query('SELECT COALESCE(MAX(Nummer), 1000) + 1 FROM Melding');
            $nummer = $stmt->fetchColumn();

            $stmt = $pdo->prepare('INSERT INTO Melding (Nummer, Type, Bericht, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd)
                                   VALUES (:nummer, :type, :bericht, 1, :opmerking, NOW(6), NOW(6))');
            $stmt->execute([
                ':nummer' => $nummer,
                ':type' => $type,
                ':bericht' => $bericht,
                ':opmerking' => $opmerking ?: null
            ]);

            header('Location: meldingen.php');
            exit;
        } catch (PDOException $e) {
            $error = 'DataBase niet verbonden';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Nieuwe melding</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="" class="form-container">
            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    <option value="">Kies type...</option>
                    <option value="voorstelling" <?php echo ($_POST['type'] ?? '') === 'voorstelling' ? 'selected' : ''; ?>>Voorstelling</option>
                    <option value="tickets" <?php echo ($_POST['type'] ?? '') === 'tickets' ? 'selected' : ''; ?>>Tickets</option>
                    <option value="service" <?php echo ($_POST['type'] ?? '') === 'service' ? 'selected' : ''; ?>>Service</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bericht">Bericht *</label>
                <textarea id="bericht" name="bericht" rows="5" required><?php echo htmlspecialchars($_POST['bericht'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="opmerking">Opmerking</label>
                <input type="text" id="opmerking" name="opmerking" value="<?php echo htmlspecialchars($_POST['opmerking'] ?? ''); ?>">
            </div>
            <button type="submit" class="knop knop-primair">Opslaan</button>
            <a href="meldingen.php" class="knop knop-secundair">Annuleren</a>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
