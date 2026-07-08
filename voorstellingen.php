<?php
// voorstellingen.php – Voorstellingen overzicht
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';

$paginaTitel = 'Voorstellingen – TheaterAurora';

$succes = isset($_GET['succes']) && $_GET['succes'] == '1';
$verwijderd = isset($_GET['verwijderd']) && $_GET['verwijderd'] == '1';

$zoekTerm = trim($_GET['zoeken'] ?? '');

$voorstellingen = []; // Query actieve voorstellingen uit database
if ($pdo !== null) {
    $sql = 'SELECT * FROM Voorstelling WHERE Isactief = 1';
    $params = [];
    if ($zoekTerm !== '') { // Filter op naam
        $sql .= ' AND Naam LIKE :zoekterm';
        $params[':zoekterm'] = "%{$zoekTerm}%";
    }
    $sql .= ' ORDER BY Datum DESC, Tijd DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $voorstellingen = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container">

    <?php if ($succes): ?>
      <div class="alert alert-success">Voorstelling succesvol bijgewerkt</div>
    <?php endif; ?>
    <?php if ($verwijderd): ?>
      <div class="alert alert-success">Voorstelling succesvol verwijderd</div>
    <?php endif; ?>

    <!-- ZOEKBALK + NIEUWE KNOP -->
    <div class="zoek-plus-knop">
      <form method="GET" action="voorstellingen.php" class="zoek-formulier">
        <input
          type="text"
          name="zoeken"
          class="zoek-input"
          placeholder="Zoeken"
          value="<?= htmlspecialchars($zoekTerm) ?>"
        />
        <button type="submit" class="knop knop-primair">Zoeken</button>
      </form>
      <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['Admin', 'Medewerker'])): ?>
        <a href="voorstelling-toevoegen.php" class="knop knop-primair">Nieuwe voorstelling</a>
      <?php endif; ?>
    </div>

    <!-- KAARTEN RASTER -->
    <div class="kaarten-raster">
      <?php if (!empty($voorstellingen)): ?>
        <?php foreach ($voorstellingen as $voorstelling): ?>
          <article class="kaart">
            <div class="kaart-afbeelding">
              <div class="afbeelding-placeholder">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                  <rect x="1" y="1" width="98" height="98" fill="none" stroke="currentColor" stroke-width="1.5"/>
                  <line x1="1" y1="1" x2="99" y2="99" stroke="currentColor" stroke-width="1.5"/>
                  <line x1="99" y1="1" x2="1" y2="99" stroke="currentColor" stroke-width="1.5"/>
                </svg>
              </div>
            </div>
            <div class="kaart-inhoud">
              <h2 class="kaart-titel"><?= htmlspecialchars($voorstelling['Naam']) ?></h2>
              <p class="kaart-tekst"><?= htmlspecialchars($voorstelling['Beschrijving'] ?? '') ?></p>
              <p class="kaart-datum-tijd">
                <?= date('d-m-Y', strtotime($voorstelling['Datum'])) ?> om <?= date('H:i', strtotime($voorstelling['Tijd'])) ?>
              </p>
              <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
                <div class="kaart-acties">
                  <a href="voorstelling-wijzigen.php?id=<?= $voorstelling['Id'] ?>" class="knop-klein knop-klein--wijzig">Wijzig</a>
                  <a
                    href="voorstelling-verwijderen.php?id=<?= $voorstelling['Id'] ?>"
                    class="knop-klein knop-klein--verwijder"
                    onclick="return bevestigVerwijderen('<?= htmlspecialchars(addslashes($voorstelling['Naam'])) ?>')"
                  >Verwijder</a>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="geen-resultaten">Geen voorstellingen gevonden.</p>
      <?php endif; ?>
    </div>

    </div>
  </main>

  <script>
    function bevestigVerwijderen(naam) {
      return confirm('Weet u zeker dat u voorstelling "' + naam + '" wilt verwijderen?');
    }
  </script>

<?php require_once 'includes/footer.php'; ?>