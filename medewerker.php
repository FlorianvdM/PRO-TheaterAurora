<?php

require_once __DIR__ . '/includes/db.php';

$paginaTitel = 'Medewerkers';

$stmt = $pdo->query(
    'SELECT m.*, g.Voornaam, g.Tussenvoegsel, g.Achternaam, g.Gebruikersnaam, c.Email
     FROM Medewerker m
     INNER JOIN Gebruiker g ON g.Id = m.GebruikerId
     LEFT JOIN Contact c ON c.GebruikerId = g.Id
     ORDER BY m.Datumaangemaakt DESC'
);
$medewerkers = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

  <main class="main-content">
    <div class="container">
      <h1 class="pagina-titel"><?php echo $paginaTitel; ?></h1>
      <p class="pagina-subtitel">Overzicht van alle medewerkers.</p>

      <div class="stats">
        <div class="stat-card">
          <span><?php echo count($medewerkers); ?></span>
          <label>Totaal medewerkers</label>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th>Naam</th>
            <th>Gebruikersnaam</th>
            <th>E-mail</th>
            <th>Nummer</th>
            <th>Medewerkersoort</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($medewerkers)): ?>
            <tr class="lege-rij">
              <td colspan="5">Geen medewerkers gevonden.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($medewerkers as $m): ?>
              <tr>
                <td><?php echo htmlspecialchars($m['Voornaam'] . ' ' . ($m['Tussenvoegsel'] ? $m['Tussenvoegsel'] . ' ' : '') . $m['Achternaam']); ?></td>
                <td><?php echo htmlspecialchars($m['Gebruikersnaam']); ?></td>
                <td><?php echo htmlspecialchars($m['Email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($m['Nummer']); ?></td>
                <td><?php echo htmlspecialchars($m['Medewerkersoort']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
