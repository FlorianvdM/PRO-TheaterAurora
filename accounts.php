<?php

require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$paginaTitel = 'Accounts – TheaterAurora Admin';

$zoekEmail = trim($_GET['email'] ?? '');

$accounts = [];
if ($pdo !== null) {
    $sql = 'SELECT g.Id, c.Email, r.Naam AS Rol, g.Voornaam, g.Tussenvoegsel, g.Achternaam
            FROM Gebruiker g
            LEFT JOIN Contact c ON c.GebruikerId = g.Id
            LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.Isactief = 1
            WHERE g.Isactief = 1';
    $params = [];
    if ($zoekEmail !== '') {
        $sql .= ' AND c.Email LIKE :email';
        $params[':email'] = "%{$zoekEmail}%";
    }
    $sql .= ' ORDER BY g.Achternaam, g.Voornaam';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $accounts = $stmt->fetchAll();
}

$succes = isset($_GET['succes']) && $_GET['succes'] == '1';
$verwijderd = isset($_GET['verwijderd']) && $_GET['verwijderd'] == '1';

require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container">

      <div class="pagina-header">
        <h1 class="sectie-titel">Accounts</h1>
        <a href="account-toevoegen.php" class="knop knop-primair">+ Nieuw account</a>
      </div>

      <?php if ($succes): ?>
        <div class="alert alert-success">Account succesvol aangemaakt</div>
      <?php endif; ?>

      <?php if ($verwijderd): ?>
        <div class="alert alert-success">Account succesvol verwijderd</div>
      <?php endif; ?>

      <!-- E-MAIL ZOEKBALK -->
      <form method="GET" action="" class="filter-form">
        <div class="filter-group">
          <input
            type="text"
            id="email"
            name="email"
            class="filter-input"
            placeholder="Zoek op e-mailadres..."
            value="<?= htmlspecialchars($zoekEmail) ?>"
          />
        </div>
        <button type="submit" class="knop knop-primair">Zoeken</button>
      </form>

      <!-- OVERZICHT ACCOUNTS TABEL -->
        <div class="table-container"><table>
          <thead>
            <tr>
              <th>Naam</th>
              <th>E-mail</th>
              <th>Rol</th>
              <th>Acties</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($accounts)): ?>
              <?php foreach ($accounts as $account): ?>
                <tr>
                  <td><?= htmlspecialchars($account['Voornaam'] . ' ' . ($account['Tussenvoegsel'] ? $account['Tussenvoegsel'] . ' ' : '') . $account['Achternaam']) ?></td>
                  <td><?= htmlspecialchars($account['Email'] ?? '') ?></td>
                  <td><?= htmlspecialchars($account['Rol'] ?? '-') ?></td>
                  <td class="cel-acties">
                    <div class="cel-acties-inner">
                      <a href="account-wijzig.php?id=<?= urlencode($account['Id']) ?>"
                         class="knop-klein knop-klein--wijzig">Wijzig</a>
                      <a href="account-verwijder.php?id=<?= urlencode($account['Id']) ?>"
                         class="knop-klein knop-klein--verwijder"
                         onclick="return confirm('Weet je zeker dat je dit account wilt verwijderen?')">
                        Verwijder
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="geen-resultaten">Geen accounts gevonden.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table></div>

    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>