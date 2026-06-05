<?php

require_once __DIR__ . '/includes/db.php';

if ($pdo === null) {
    require_once __DIR__ . '/includes/header.php';
    exit;
}

$paginaTitel = 'Medewerkers';

$zoekterm = $_GET['zoeken'] ?? '';
$filterSoort = $_GET['soort'] ?? '';
$filterStatus = $_GET['status'] ?? '';

$sql = 'SELECT m.*, g.Voornaam, g.Tussenvoegsel, g.Achternaam, g.Gebruikersnaam,
               c.Email, c.Mobiel, r.Naam AS RolNaam
        FROM Medewerker m
        INNER JOIN Gebruiker g ON g.Id = m.GebruikerId
        LEFT JOIN Contact c ON c.GebruikerId = g.Id
        LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.Isactief = 1
        WHERE 1=1';

$params = [];

if ($zoekterm !== '') {
    $sql .= ' AND (g.Voornaam LIKE :zoekterm1
               OR g.Achternaam LIKE :zoekterm2
               OR g.Gebruikersnaam LIKE :zoekterm3
               OR m.Nummer LIKE :zoekterm4)';
    $params[':zoekterm1'] = "%{$zoekterm}%";
    $params[':zoekterm2'] = "%{$zoekterm}%";
    $params[':zoekterm3'] = "%{$zoekterm}%";
    $params[':zoekterm4'] = "%{$zoekterm}%";
}

if ($filterSoort !== '') {
    $sql .= ' AND m.Medewerkersoort = :soort';
    $params[':soort'] = $filterSoort;
}

if ($filterStatus !== '') {
    $sql .= ' AND m.Isactief = :status';
    $params[':status'] = $filterStatus === 'actief' ? 1 : 0;
}

$sql .= ' ORDER BY m.Datumaangemaakt DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$medewerkers = $stmt->fetchAll();

$stmtSoorten = $pdo->query('SELECT DISTINCT Medewerkersoort FROM Medewerker ORDER BY Medewerkersoort');
$soorten = $stmtSoorten->fetchAll(PDO::FETCH_COLUMN);

$aantalActief = 0;
$aantalInactief = 0;
$soortCounts = [];
foreach ($medewerkers as $m) {
    if ($m['Isactief']) {
        $aantalActief++;
    } else {
        $aantalInactief++;
    }
    $soort = $m['Medewerkersoort'];
    if (!isset($soortCounts[$soort])) {
        $soortCounts[$soort] = 0;
    }
    $soortCounts[$soort]++;
}
require_once __DIR__ . '/includes/header.php';
?>

  <main class="main-content">
    <div class="container">
      <div class="pagina-header">
        <div>
          <h1 class="pagina-titel"><?php echo $paginaTitel; ?></h1>
          <p class="pagina-subtitel">Overzicht van alle medewerkers.</p>
        </div>
        <a href="medewerker-toevoegen.php" class="knop knop-primair">+ Nieuwe medewerker</a>
      </div>

      <div class="stats">
        <div class="stat-card">
          <span><?php echo count($medewerkers); ?></span>
          <label>Totaal</label>
        </div>
        <div class="stat-card stat-card--actief">
          <span><?php echo $aantalActief; ?></span>
          <label>Actief</label>
        </div>
        <div class="stat-card stat-card--inactief">
          <span><?php echo $aantalInactief; ?></span>
          <label>Inactief</label>
        </div>
        <?php foreach ($soortCounts as $soort => $count): ?>
          <div class="stat-card">
            <span><?php echo $count; ?></span>
            <label><?php echo htmlspecialchars($soort); ?></label>
          </div>
        <?php endforeach; ?>
      </div>

      <form class="filter-form" method="get" action="">
        <div class="filter-group">
          <input type="text" name="zoeken" class="filter-input" placeholder="Zoeken op naam, gebruikersnaam of nummer..." value="<?php echo htmlspecialchars($zoekterm); ?>">
        </div>
        <div class="filter-group">
          <select name="soort" class="filter-select">
            <option value="">Alle soorten</option>
            <?php foreach ($soorten as $soort): ?>
              <option value="<?php echo htmlspecialchars($soort); ?>" <?php echo $filterSoort === $soort ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($soort); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <select name="status" class="filter-select">
            <option value="">Alle statussen</option>
            <option value="actief" <?php echo $filterStatus === 'actief' ? 'selected' : ''; ?>>Actief</option>
            <option value="inactief" <?php echo $filterStatus === 'inactief' ? 'selected' : ''; ?>>Inactief</option>
          </select>
        </div>
        <button type="submit" class="knop knop-primair">Filteren</button>
        <?php if ($zoekterm !== '' || $filterSoort !== '' || $filterStatus !== ''): ?>
          <a href="medewerker.php" class="knop knop-secundair">Reset</a>
        <?php endif; ?>
      </form>
      <table>
        <thead>
          <tr>
            <th>Naam</th>
            <th>Gebruikersnaam</th>
            <th>E-mail</th>
            <th>Mobiel</th>
            <th>Nummer</th>
            <th>Soort</th>
            <th>Rol</th>
            <th>Status</th>
            <th>Datum aangemaakt</th>
            <th>Acties</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($medewerkers)): ?>
            <tr class="lege-rij">
              <td colspan="10">Geen medewerkers gevonden.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($medewerkers as $m): ?>
              <tr>
                <td class="cel-naam">
                  <span class="naam-volledig"><?php echo htmlspecialchars($m['Voornaam'] . ' ' . ($m['Tussenvoegsel'] ? $m['Tussenvoegsel'] . ' ' : '') . $m['Achternaam']); ?></span>
                </td>
                <td><?php echo htmlspecialchars($m['Gebruikersnaam']); ?></td>
                <td><?php echo htmlspecialchars($m['Email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($m['Mobiel'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($m['Nummer']); ?></td>
                <td><span class="soort-badge"><?php echo htmlspecialchars($m['Medewerkersoort']); ?></span></td>
                <td><?php echo htmlspecialchars($m['RolNaam'] ?? '-'); ?></td>
                <td>
                  <?php if ($m['Isactief']): ?>
                    <span class="status-badge status-badge--actief">Actief</span>
                  <?php else: ?>
                    <span class="status-badge status-badge--inactief">Inactief</span>
                  <?php endif; ?>
                </td>
                <td class="cel-datum"><?php echo date('d-m-Y', strtotime($m['Datumaangemaakt'])); ?></td>
                <td class="cel-acties">
                  <a href="medewerker-wijzigen.php?id=<?php echo $m['Id']; ?>" class="knop-klein knop-klein--wijzig">Wijzig</a>
                  <a href="medewerker-verwijderen.php?id=<?php echo $m['Id']; ?>" class="knop-klein knop-klein--verwijder" onclick="return bevestigVerwijderen('<?php echo htmlspecialchars($m['Voornaam'] . ' ' . ($m['Tussenvoegsel'] ? $m['Tussenvoegsel'] . ' ' : '') . $m['Achternaam']); ?>')">Verwijder</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    function bevestigVerwijderen(naam) {
      return confirm('Weet u zeker dat u medewerker "' + naam + '" wilt verwijderen?');
    }
  </script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
