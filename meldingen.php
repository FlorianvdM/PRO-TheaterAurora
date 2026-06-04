<?php
// ============================================
// meldingen.php
// TheaterAurora – Meldingen pagina
// ============================================

$paginaTitel = 'Meldingen – TheaterAurora';
$paginaCss   = 'assets/css/meldingen.css';

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

// Voorbeelddata – later te vervangen door een database-query
$meldingen = [
  ['type' => 'voorstelling', 'tekst' => 'Voorstelling "De Storm" begint over 30 minuten.'],
  ['type' => 'tickets',      'tekst' => 'Uw tickets voor vrijdag zijn bevestigd.'],
  ['type' => 'service',      'tekst' => 'Onderhoud gepland op zaterdag 08:00–10:00.'],
  ['type' => 'voorstelling', 'tekst' => 'Nieuwe voorstelling toegevoegd: "Hamlet".'],
  ['type' => 'tickets',      'tekst' => 'U heeft nog 2 tickets gereserveerd staan.'],
];

// Filteren op server-side als filter actief is
$gefilterdeMeldingen = ($actiefFilter === 'alle')
  ? $meldingen
  : array_filter($meldingen, fn($m) => $m['type'] === $actiefFilter);

require_once 'includes/header.php';
?>

  <main class="main-content">
    <section class="meldingen-sectie">
      <h1 class="sectie-titel">Meldingen</h1>

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
        <?php if (!empty($gefilterdeMeldingen)): ?>
          <?php foreach ($gefilterdeMeldingen as $melding): ?>
            <li class="melding-item" data-type="<?= htmlspecialchars($melding['type']) ?>">
              <?= htmlspecialchars($melding['tekst']) ?>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="melding-item geen-resultaten">Geen meldingen gevonden.</li>
        <?php endif; ?>
      </ul>

    </section>
  </main>

<?php require_once 'includes/footer.php'; ?>
