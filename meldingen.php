<?php
// ============================================
// meldingen.php
// TheaterAurora – Meldingen pagina
// ============================================

require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Meldingen – TheaterAurora';

// Beschikbare filter-categorieën
$filterOpties = [
  'alle'         => 'Alle',
  'voorstelling' => 'Voorstelling',
  'tickets'      => 'Tickets',
  'service'      => 'Service',
];

// Actieve filter ophalen uit URL (?filter=tickets), standaard 'alle'
$actiefFilter = $_GET['filter'] ?? 'alle';
if (!array_key_exists($actiefFilter, $filterOpties)) {
  $actiefFilter = 'alle';
}

// Feedback na het versturen van een melding naar bezoekers
$verzonden = isset($_GET['verzonden']) && $_GET['verzonden'] == '1';
$aantalBezoekers = isset($_GET['aantal']) ? (int) $_GET['aantal'] : 0;

// Meldingen ophalen uit database (met optionele filter)
$meldingen = [];
if ($pdo !== null) {
  try {
    if ($actiefFilter === 'alle') {
      $stmt = $pdo->query('SELECT Id, Type, Bericht FROM Melding WHERE Isactief = 1 ORDER BY Datumaangemaakt DESC');
    } else {
      $stmt = $pdo->prepare('SELECT Id, Type, Bericht FROM Melding WHERE Isactief = 1 AND Type = :type ORDER BY Datumaangemaakt DESC');
      $stmt->execute([':type' => $actiefFilter]);
    }
    $meldingen = $stmt->fetchAll();
  } catch (PDOException $e) {
    // Geen meldingen bij fout
  }
}

require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container">

      <div class="pagina-header">
        <div>
          <h1 class="sectie-titel">Meldingen</h1>
        </div>
        <a href="melding-toevoegen.php" class="knop knop-primair">+ Nieuwe melding</a>
      </div>

      <?php if ($verzonden): ?>
        <div class="alert alert-success">
          Melding succesvol verstuurd naar <?= $aantalBezoekers ?> bezoeker<?= $aantalBezoekers === 1 ? '' : 's' ?>.
        </div>
      <?php endif; ?>

      <!-- FILTER TABS -->
      <div class="filter-tabs">
        <?php foreach ($filterOpties as $sleutel => $label): ?>
          <a href="?filter=<?= urlencode($sleutel) ?>"
             class="tab <?= $actiefFilter === $sleutel ? 'actief' : '' ?>"
             data-filter="<?= htmlspecialchars($sleutel) ?>">
            <?= htmlspecialchars($label) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- MELDINGEN LIJST -->
      <ul class="meldingen-lijst" id="meldingen-lijst">
        <?php if (!empty($meldingen)): ?>
          <?php foreach ($meldingen as $melding): ?>
            <li class="melding-item" data-type="<?= htmlspecialchars($melding['Type']) ?>">
              <span class="melding-tekst"><?= htmlspecialchars($melding['Bericht']) ?></span>
              <a
                href="melding-versturen.php?id=<?= $melding['Id'] ?>"
                class="knop-klein knop-klein--verstuur"
                onclick="return bevestigVersturen()"
              >Verstuur</a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="melding-item geen-resultaten">Geen meldingen gevonden.</li>
        <?php endif; ?>
      </ul>

    </div>
  </main>

  <script>
    function bevestigVersturen() {
      return confirm('Weet u zeker dat u deze melding wilt versturen naar alle bezoekers?');
    }
  </script>

<?php require_once 'includes/footer.php'; ?>