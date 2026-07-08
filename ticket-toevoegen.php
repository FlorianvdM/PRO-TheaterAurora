<?php
// ticket-toevoegen.php – Nieuw ticket formulier (Admin/Medewerker)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Nieuw ticket';
$error = null;

$bezoekers = [];
$voorstellingen = [];
$prijzen = [];

if ($pdo !== null) {
    $bezoekers = $pdo->query('
        SELECT b.Id, g.Voornaam, g.Tussenvoegsel, g.Achternaam
        FROM Bezoeker b
        INNER JOIN Gebruiker g ON g.Id = b.GebruikerId
        WHERE b.Isactief = 1
        ORDER BY g.Achternaam
    ')->fetchAll();

    $voorstellingen = $pdo->query('
        SELECT Id, Naam, Datum, Tijd FROM Voorstelling WHERE Isactief = 1 ORDER BY Datum DESC
    ')->fetchAll();

    $prijzen = $pdo->query('
        SELECT Id, Tarief FROM Prijs WHERE Isactief = 1 ORDER BY Tarief
    ')->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bezoekerId = $_POST['bezoeker_id'] ?? '';
    $voorstellingId = $_POST['voorstelling_id'] ?? '';
    $prijsId = $_POST['prijs_id'] ?? '';
    $datum = $_POST['datum'] ?? '';
    $tijd = $_POST['tijd'] ?? '';
    $status = $_POST['status'] ?? '';
    $opmerking = $_POST['opmerking'] ?? '';

    if (empty($bezoekerId) || empty($voorstellingId) || empty($prijsId) || empty($datum) || empty($tijd) || empty($status)) {
        $error = 'Niet alle gegevens correct ingevuld';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            $stmt = $pdo->query('SELECT COALESCE(MAX(Nummer), 1000) + 1 FROM Ticket');
            $nummer = $stmt->fetchColumn();

            $barcode = strtoupper(substr(uniqid(), 0, 8)) . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $barcode = substr($barcode, 0, 14);

            $stmt = $pdo->prepare('
                INSERT INTO Ticket (BezoekerId, VoorstellingId, PrijsId, Nummer, Barcode, Datum, Tijd, Status, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd)
                VALUES (:bezoekerId, :voorstellingId, :prijsId, :nummer, :barcode, :datum, :tijd, :status, 1, :opmerking, NOW(6), NOW(6))
            ');
            $stmt->execute([
                ':bezoekerId' => $bezoekerId,
                ':voorstellingId' => $voorstellingId,
                ':prijsId' => $prijsId,
                ':nummer' => $nummer,
                ':barcode' => $barcode,
                ':datum' => $datum,
                ':tijd' => $tijd,
                ':status' => $status,
                ':opmerking' => $opmerking ?: null
            ]);

            header('Location: tickets.php?succes=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Niet alle gegevens correct ingevuld';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1>Nieuw ticket</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="" class="form-container">
            <div class="form-group">
                <label for="bezoeker_id">Bezoeker *</label>
                <select id="bezoeker_id" name="bezoeker_id" required>
                    <option value="">Kies bezoeker...</option>
                    <?php foreach ($bezoekers as $b): ?>
                        <?php $naam = $b['Voornaam'] . ($b['Tussenvoegsel'] ? ' ' . $b['Tussenvoegsel'] : '') . ' ' . $b['Achternaam']; ?>
                        <option value="<?php echo $b['Id']; ?>" <?php echo ($_POST['bezoeker_id'] ?? '') == $b['Id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($naam); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="voorstelling_id">Voorstelling *</label>
                <select id="voorstelling_id" name="voorstelling_id" required onchange="vulDatumTijd(this)">
                    <option value="">Kies voorstelling...</option>
                    <?php foreach ($voorstellingen as $v): ?>
                        <option value="<?php echo $v['Id']; ?>"
                            data-datum="<?php echo $v['Datum']; ?>"
                            data-tijd="<?php echo $v['Tijd']; ?>"
                            <?php echo ($_POST['voorstelling_id'] ?? '') == $v['Id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($v['Naam']) . ' — ' . date('d-m-Y', strtotime($v['Datum'])) . ' ' . date('H:i', strtotime($v['Tijd'])); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="prijs_id">Prijs *</label>
                <select id="prijs_id" name="prijs_id" required>
                    <option value="">Kies prijs...</option>
                    <?php foreach ($prijzen as $p): ?>
                        <option value="<?php echo $p['Id']; ?>" <?php echo ($_POST['prijs_id'] ?? '') == $p['Id'] ? 'selected' : ''; ?>>
                            &euro;<?php echo number_format($p['Tarief'], 2, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="datum">Datum *</label>
                <input type="date" id="datum" name="datum" value="<?php echo htmlspecialchars($_POST['datum'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="tijd">Tijd *</label>
                <input type="time" id="tijd" name="tijd" value="<?php echo htmlspecialchars($_POST['tijd'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="">Kies status...</option>
                    <option value="Besteld" <?php echo ($_POST['status'] ?? '') === 'Besteld' ? 'selected' : ''; ?>>Besteld</option>
                    <option value="Gereserveerd" <?php echo ($_POST['status'] ?? '') === 'Gereserveerd' ? 'selected' : ''; ?>>Gereserveerd</option>
                    <option value="Geannuleerd" <?php echo ($_POST['status'] ?? '') === 'Geannuleerd' ? 'selected' : ''; ?>>Geannuleerd</option>
                </select>
            </div>
            <div class="form-group">
                <label for="opmerking">Opmerking</label>
                <input type="text" id="opmerking" name="opmerking" value="<?php echo htmlspecialchars($_POST['opmerking'] ?? ''); ?>">
            </div>
            <button type="submit" class="knop knop-primair">Opslaan</button>
            <a href="tickets.php" class="knop knop-secundair">Annuleren</a>
        </form>
    </div>
</main>

<script>
function vulDatumTijd(select) {
    var opt = select.options[select.selectedIndex];
    if (opt && opt.dataset.datum) {
        document.getElementById('datum').value = opt.dataset.datum;
        document.getElementById('tijd').value = opt.dataset.tijd;
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
