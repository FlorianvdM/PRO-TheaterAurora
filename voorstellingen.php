<?php
// ============================================
// voorstellingen.php
// TheaterAurora – Voorstellingen overzicht
// ============================================

$paginaTitel = 'Voorstellingen – TheaterAurora';

// Voorbeelddata – later te vervangen door database-query
$voorstellingen = [
  [
    'id'          => 1,
    'titel'       => 'De Storm',
    'beschrijving'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'afbeelding'  => 'assets/images/voorstelling-1.jpg',
  ],
  [
    'id'          => 2,
    'titel'       => 'Hamlet',
    'beschrijving'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'afbeelding'  => 'assets/images/voorstelling-2.jpg',
  ],
  [
    'id'          => 3,
    'titel'       => 'Othello',
    'beschrijving'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'afbeelding'  => 'assets/images/voorstelling-3.jpg',
  ],
  [
    'id'          => 4,
    'titel'       => 'Macbeth',
    'beschrijving'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'afbeelding'  => 'assets/images/voorstelling-4.jpg',
  ],
  [
    'id'          => 5,
    'titel'       => 'Romeo & Julia',
    'beschrijving'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'afbeelding'  => 'assets/images/voorstelling-5.jpg',
  ],
];

// Zoekfilter op titel
$zoekTerm = trim($_GET['zoeken'] ?? '');
if ($zoekTerm !== '') {
  $voorstellingen = array_filter(
    $voorstellingen,
    fn($v) => str_contains(strtolower($v['titel']), strtolower($zoekTerm))
  );
}

require_once 'includes/header.php';
?>

  <main class="main-content">

    <!-- ZOEKBALK -->
    <form method="GET" action="voorstellingen.php" class="zoek-formulier">
      <input
        type="text"
        name="zoeken"
        class="zoek-input"
        placeholder="Zoeken"
        value="<?= htmlspecialchars($zoekTerm) ?>"
      />
    </form>


    <!-- KAARTEN RASTER -->
    <div class="kaarten-raster">
      <?php if (!empty($voorstellingen)): ?>
        <?php foreach ($voorstellingen as $voorstelling): ?>
          <article class="kaart">
            <div class="kaart-afbeelding">
              <?php if (!empty($voorstelling['afbeelding']) && file_exists($voorstelling['afbeelding'])): ?>
                <img
                  src="<?= htmlspecialchars($voorstelling['afbeelding']) ?>"
                  alt="<?= htmlspecialchars($voorstelling['titel']) ?>"
                />
              <?php else: ?>
                <!-- Placeholder als afbeelding ontbreekt -->
                <div class="afbeelding-placeholder">
                  <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect x="1" y="1" width="98" height="98" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="1" y1="1" x2="99" y2="99" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="99" y1="1" x2="1" y2="99" stroke="currentColor" stroke-width="1.5"/>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
            <div class="kaart-inhoud">
              <h2 class="kaart-titel"><?= htmlspecialchars($voorstelling['titel']) ?></h2>
              <p class="kaart-tekst"><?= htmlspecialchars($voorstelling['beschrijving']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="geen-resultaten">Geen voorstellingen gevonden.</p>
      <?php endif; ?>
    </div>

  </main>

<?php require_once 'includes/footer.php'; ?>
