<?php
// ============================================
// accounts.php
// TheaterAurora – Accounts overzicht (admin)
// ============================================

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin']);

$paginaTitel = 'Accounts – TheaterAurora Admin';

// Voorbeelddata – later te vervangen door database-query
$accounts = [
  ['id' => 1, 'email' => 'jan.jansen@theater.nl', 'rol' => 'Medewerker'],
  ['id' => 2, 'email' => 'lisa.vos@theater.nl', 'rol' => 'Admin'],
  ['id' => 3, 'email' => 'peter.bakker@theater.nl', 'rol' => 'Medewerker'],
  ['id' => 4, 'email' => 'sara.de.wit@theater.nl', 'rol' => 'Medewerker'],
];

// Zoekfilter op e-mail (filtert mock data)
$zoekEmail = trim($_GET['email'] ?? '');
if ($zoekEmail !== '') {
  $accounts = array_filter(
    $accounts,
    fn($a) => str_contains(strtolower($a['email']), strtolower($zoekEmail))
  );
}

require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container">

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
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>E-mail</th>
              <th>Rol</th>
              <th>Acties</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($accounts)): ?>
              <?php foreach ($accounts as $account): ?>
                <tr>
                  <td><?= htmlspecialchars($account['id']) ?></td>
                  <td><?= htmlspecialchars($account['email']) ?></td>
                  <td><?= htmlspecialchars($account['rol']) ?></td>
                  <td class="cel-acties">
                    <a href="account-wijzig.php?id=<?= urlencode($account['id']) ?>"
                       class="knop-klein knop-klein--wijzig">Wijzig</a>
                    <a href="account-verwijder.php?id=<?= urlencode($account['id']) ?>"
                       class="knop-klein knop-klein--verwijder"
                       onclick="return confirm('Weet je zeker dat je dit account wilt verwijderen?')">
                      Verwijder
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="geen-resultaten">Geen accounts gevonden.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>