<?php
// ============================================
// meldingen.php
// TheaterAurora – Meldingen pagina
// ============================================

require_once __DIR__ . '/includes/db.php';

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

// Meldingen ophalen uit database
$meldingen = [];
if ($pdo !== null) {
  try {
    if ($actiefFilter === 'alle') {
      $stmt = $pdo->query('SELECT Type, Bericht FROM Melding WHERE Isactief = 1 ORDER BY Datumaangemaakt DESC');
    } else {
      $stmt = $pdo->prepare('SELECT Type, Bericht FROM Melding WHERE Isactief = 1 AND Type = :type ORDER BY Datumaangemaakt DESC');
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
              <?= htmlspecialchars($melding['Bericht']) ?>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="melding-item geen-resultaten">Geen meldingen gevonden.</li>
        <?php endif; ?>
      </ul>

    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>
