<?php
// voorstelling-toevoegen.php – Nieuw voorstelling formulier (Admin/Medewerker)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Nieuwe voorstelling';
$error = null;
$vandaag = date('Y-m-d');
$nu = date('H:i');

$medewerkers = []; // Medewerkers ophalen voor dropdown
if ($pdo !== null) {
    $stmt = $pdo->query('
        SELECT m.Id, g.Voornaam, g.Tussenvoegsel, g.Achternaam
        FROM Medewerker m
        INNER JOIN Gebruiker g ON g.Id = m.GebruikerId
        WHERE m.Isactief = 1
        ORDER BY g.Achternaam
    ');
    $medewerkers = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Formulier verzonden
    $medewerkerId = $_POST['medewerker_id'] ?? '';
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $datum = $_POST['datum'] ?? '';
    $tijd = $_POST['tijd'] ?? '';
    $maxAantalTickets = $_POST['max_aantal_tickets'] ?? '';
    $beschikbaarheid = $_POST['beschikbaarheid'] ?? '';
    $opmerking = $_POST['opmerking'] ?? '';

    if (empty($medewerkerId) || empty($naam) || empty($datum) || empty($tijd) || empty($maxAantalTickets) || empty($beschikbaarheid)) { // Validatie
        $error = 'Vul alle verplichte velden in.';
    } elseif ($datum < $vandaag) {
        $error = 'Een datum in het verleden is niet meer mogelijk.';
    } elseif ($datum === $vandaag && $tijd < $nu) {
        $error = 'Een tijd in het verleden is niet meer mogelijk.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try { // INSERT in database
            $stmt = $pdo->prepare('
                INSERT INTO Voorstelling (MedewerkerId, Naam, Beschrijving, Datum, Tijd, MaxAantalTickets, Beschikbaarheid, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd)
                VALUES (:medewerkerId, :naam, :beschrijving, :datum, :tijd, :maxAantalTickets, :beschikbaarheid, 1, :opmerking, NOW(6), NOW(6))
            ');
            $stmt->execute([
                ':medewerkerId' => $medewerkerId,
                ':naam' => $naam,
                ':beschrijving' => $beschrijving ?: null,
                ':datum' => $datum,
                ':tijd' => $tijd,
                ':maxAantalTickets' => $maxAantalTickets,
                ':beschikbaarheid' => $beschikbaarheid,
                ':opmerking' => $opmerking ?: null
            ]);

            header('Location: voorstellingen.php');
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
        <h1>Nieuwe voorstelling</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="" class="form-container">
            <div class="form-group">
                <label for="medewerker_id">Verantwoordelijke medewerker *</label>
                <select id="medewerker_id" name="medewerker_id" required>
                    <option value="">Kies medewerker...</option>
                    <?php foreach ($medewerkers as $m): ?>
                        <?php $fullName = $m['Voornaam'] . ($m['Tussenvoegsel'] ? ' ' . $m['Tussenvoegsel'] : '') . ' ' . $m['Achternaam']; ?>
                        <option value="<?php echo $m['Id']; ?>" <?php echo ($_POST['medewerker_id'] ?? '') == $m['Id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($fullName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="naam">Naam *</label>
                <input type="text" id="naam" name="naam" value="<?php echo htmlspecialchars($_POST['naam'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="beschrijving">Beschrijving</label>
                <textarea id="beschrijving" name="beschrijving" rows="5"><?php echo htmlspecialchars($_POST['beschrijving'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="datum">Datum *</label>
                <input type="date" id="datum" name="datum" value="<?php echo htmlspecialchars($_POST['datum'] ?? ''); ?>" required>
                <p id="datum-melding" class="veld-fout" hidden>Een datum in het verleden is niet meer mogelijk.</p>
            </div>
            <div class="form-group">
                <label for="tijd">Tijd *</label>
                <input type="time" id="tijd" name="tijd" value="<?php echo htmlspecialchars($_POST['tijd'] ?? ''); ?>" required>
                <p id="tijd-melding" class="veld-fout" hidden>Een tijd in het verleden is niet meer mogelijk.</p>
            </div>
            <div class="form-group">
                <label for="max_aantal_tickets">Max aantal tickets *</label>
                <input type="number" id="max_aantal_tickets" name="max_aantal_tickets" min="1" value="<?php echo htmlspecialchars($_POST['max_aantal_tickets'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="beschikbaarheid">Beschikbaarheid *</label>
                <select id="beschikbaarheid" name="beschikbaarheid" required>
                    <option value="">Kies beschikbaarheid...</option>
                    <option value="Beschikbaar" <?php echo ($_POST['beschikbaarheid'] ?? '') === 'Beschikbaar' ? 'selected' : ''; ?>>Beschikbaar</option>
                    <option value="Uitverkocht" <?php echo ($_POST['beschikbaarheid'] ?? '') === 'Uitverkocht' ? 'selected' : ''; ?>>Uitverkocht</option>
                    <option value="Geannuleerd" <?php echo ($_POST['beschikbaarheid'] ?? '') === 'Geannuleerd' ? 'selected' : ''; ?>>Geannuleerd</option>
                </select>
            </div>
            <div class="form-group">
                <label for="opmerking">Opmerking</label>
                <input type="text" id="opmerking" name="opmerking" value="<?php echo htmlspecialchars($_POST['opmerking'] ?? ''); ?>">
            </div>
            <button type="submit" class="knop knop-primair">Opslaan</button>
            <a href="voorstellingen.php" class="knop knop-secundair">Annuleren</a>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const datumInput = document.getElementById('datum');
    const tijdInput = document.getElementById('tijd');
    const datumMelding = document.getElementById('datum-melding');
    const tijdMelding = document.getElementById('tijd-melding');
    const vandaag = '<?php echo $vandaag; ?>';
    const nu = '<?php echo $nu; ?>';

    function controleerDatumEnTijd() {
        const datumVerleden = datumInput.value && datumInput.value < vandaag;
        const tijdVerleden = datumInput.value === vandaag && tijdInput.value && tijdInput.value < nu;

        datumMelding.hidden = !datumVerleden;
        tijdMelding.hidden = !tijdVerleden;

        datumInput.setCustomValidity(datumVerleden ? 'Een datum in het verleden is niet meer mogelijk.' : '');
        tijdInput.setCustomValidity(tijdVerleden ? 'Een tijd in het verleden is niet meer mogelijk.' : '');
    }

    datumInput.addEventListener('input', controleerDatumEnTijd);
    datumInput.addEventListener('change', controleerDatumEnTijd);
    tijdInput.addEventListener('input', controleerDatumEnTijd);
    tijdInput.addEventListener('change', controleerDatumEnTijd);
    controleerDatumEnTijd();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
