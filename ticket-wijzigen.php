<?php
// ticket-wijzigen.php - Bestaand ticket wijzigen (Admin/Medewerker)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Ticket wijzigen';
$error = null;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ticket = null;
$bezoekers = [];
$voorstellingen = [];
$prijzen = [];
$statusOpties = ['Besteld', 'Gereserveerd', 'Geannuleerd'];

if ($id <= 0) {
    header('Location: tickets.php?fout=1');
    exit;
}

if ($pdo !== null) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Ticket WHERE Id = :id AND Isactief = 1');
        $stmt->execute([':id' => $id]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            header('Location: tickets.php?fout=1');
            exit;
        }

        // Keuzelijsten voor het wijzigen van gekoppelde ticketgegevens.
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
    } catch (PDOException $e) {
        $error = 'Ticket kan niet worden geladen.';
    }
}

$formData = $ticket ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'BezoekerId' => $_POST['bezoeker_id'] ?? '',
        'VoorstellingId' => $_POST['voorstelling_id'] ?? '',
        'PrijsId' => $_POST['prijs_id'] ?? '',
        'Datum' => $_POST['datum'] ?? '',
        'Tijd' => $_POST['tijd'] ?? '',
        'Status' => $_POST['status'] ?? '',
        'Opmerking' => $_POST['opmerking'] ?? '',
    ];

    if (
        empty($formData['BezoekerId']) ||
        empty($formData['VoorstellingId']) ||
        empty($formData['PrijsId']) ||
        empty($formData['Datum']) ||
        empty($formData['Tijd']) ||
        empty($formData['Status'])
    ) {
        $error = 'Niet alle gegevens correct ingevuld';
    } elseif (!in_array($formData['Status'], $statusOpties, true)) {
        $error = 'Ongeldige ticketstatus gekozen';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            $stmt = $pdo->prepare('
                UPDATE Ticket
                SET BezoekerId = :bezoekerId,
                    VoorstellingId = :voorstellingId,
                    PrijsId = :prijsId,
                    Datum = :datum,
                    Tijd = :tijd,
                    Status = :status,
                    Opmerking = :opmerking,
                    Datumgewijzigd = NOW(6)
                WHERE Id = :id AND Isactief = 1
            ');
            $stmt->execute([
                ':bezoekerId' => $formData['BezoekerId'],
                ':voorstellingId' => $formData['VoorstellingId'],
                ':prijsId' => $formData['PrijsId'],
                ':datum' => $formData['Datum'],
                ':tijd' => $formData['Tijd'],
                ':status' => $formData['Status'],
                ':opmerking' => $formData['Opmerking'] ?: null,
                ':id' => $id,
            ]);

            header('Location: tickets.php?gewijzigd=1');
            exit;
        } catch (PDOException $e) {
            $error = 'De wijzigingen kunnen niet worden opgeslagen.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
  <div class="container">
    <h1>Ticket wijzigen</h1>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="ticket-wijzigen.php?id=<?php echo urlencode($id); ?>" class="form-container">
      <div class="form-group">
        <label for="bezoeker_id">Bezoeker *</label>
        <select id="bezoeker_id" name="bezoeker_id" required>
          <option value="">Kies bezoeker...</option>
          <?php foreach ($bezoekers as $b): ?>
            <?php $naam = $b['Voornaam'] . ($b['Tussenvoegsel'] ? ' ' . $b['Tussenvoegsel'] : '') . ' ' . $b['Achternaam']; ?>
            <option value="<?php echo $b['Id']; ?>" <?php echo (string) ($formData['BezoekerId'] ?? '') === (string) $b['Id'] ? 'selected' : ''; ?>>
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
            <option
              value="<?php echo $v['Id']; ?>"
              data-datum="<?php echo $v['Datum']; ?>"
              data-tijd="<?php echo $v['Tijd']; ?>"
              <?php echo (string) ($formData['VoorstellingId'] ?? '') === (string) $v['Id'] ? 'selected' : ''; ?>
            >
              <?php echo htmlspecialchars($v['Naam']) . ' - ' . date('d-m-Y', strtotime($v['Datum'])) . ' ' . date('H:i', strtotime($v['Tijd'])); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="prijs_id">Prijs *</label>
        <select id="prijs_id" name="prijs_id" required>
          <option value="">Kies prijs...</option>
          <?php foreach ($prijzen as $p): ?>
            <option value="<?php echo $p['Id']; ?>" <?php echo (string) ($formData['PrijsId'] ?? '') === (string) $p['Id'] ? 'selected' : ''; ?>>
              &euro;<?php echo number_format($p['Tarief'], 2, ',', '.'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="datum">Datum *</label>
        <input type="date" id="datum" name="datum" value="<?php echo htmlspecialchars($formData['Datum'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label for="tijd">Tijd *</label>
        <input type="time" id="tijd" name="tijd" value="<?php echo htmlspecialchars(substr($formData['Tijd'] ?? '', 0, 5)); ?>" required>
      </div>

      <div class="form-group">
        <label for="status">Status *</label>
        <select id="status" name="status" required>
          <option value="">Kies status...</option>
          <?php foreach ($statusOpties as $status): ?>
            <option value="<?php echo htmlspecialchars($status); ?>" <?php echo ($formData['Status'] ?? '') === $status ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($status); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="opmerking">Opmerking</label>
        <input type="text" id="opmerking" name="opmerking" value="<?php echo htmlspecialchars($formData['Opmerking'] ?? ''); ?>">
      </div>

      <div class="form-acties">
        <button type="submit" class="knop knop-primair">Wijzigingen opslaan</button>
        <a href="tickets.php" class="knop knop-secundair">Annuleren</a>
      </div>
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
