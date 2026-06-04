<?php
// ============================================
// accounts.php
// TheaterAurora – Accounts overzicht (admin)
// ============================================

$paginaTitel = 'Accounts – TheaterAurora Admin';
$paginaCss = 'assets/css/accounts.css';

// Voorbeelddata – later te vervangen door database-query
$accounts = [
  ['id' => 1, 'email' => 'jan.jansen@theater.nl', 'rol' => 'Medewerker'],
  ['id' => 2, 'email' => 'lisa.vos@theater.nl', 'rol' => 'Admin'],
  ['id' => 3, 'email' => 'peter.bakker@theater.nl', 'rol' => 'Medewerker'],
  ['id' => 4, 'email' => 'sara.de.wit@theater.nl', 'rol' => 'Medewerker'],
];

// Zoekfilter op e-mail
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
    <section class="accounts-sectie">

      <!-- E-MAIL ZOEKBALK -->
      <form method="GET" action="" class="zoek-formulier">
        <label for="email" class="zoek-label">E-mail</label>
        <div class="zoek-rij">
          <input
            type="text"
            id="email"
            name="email"
            class="zoek-input"
            placeholder="Zoek op e-mailadres..."
            value="<?= htmlspecialchars($zoekEmail) ?>"
          />
          <button type="submit" class="zoek-knop">Zoeken</button>
        </div>
      </form>

      <!-- OVERZICHT ACCOUNTS TABEL -->
      <div class="tabel-wrapper">
        <table class="accounts-tabel">
          <thead>
            <tr>
              <th>ID</th>
              <th>E-mail</th>
              <th>Rol</th>
              <th class="th-acties">Acties</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($accounts)): ?>
              <?php foreach ($accounts as $account): ?>
                <tr>
                  <td><?= htmlspecialchars($account['id']) ?></td>
                  <td><?= htmlspecialchars($account['email']) ?></td>
                  <td><?= htmlspecialchars($account['rol']) ?></td>
                  <td class="td-acties">
                    <a href="account-wijzig.php?id=<?= urlencode($account['id']) ?>"
                       class="knop knop-wijzig">Wijzig</a>
                    <a href="account-verwijder.php?id=<?= urlencode($account['id']) ?>"
                       class="knop knop-verwijder"
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

        <?php if (empty($accounts)): ?>
          <p class="tabel-leeg-label">Overzicht accounts</p>
        <?php endif; ?>
      </div>

    </section>
  </main>

<?php require_once 'includes/footer.php'; ?>