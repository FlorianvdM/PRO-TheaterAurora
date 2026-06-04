<?php
// ============================================
// accounts.php (voorheen medewerker.php)
// TheaterAurora – Accounts overzicht
// ============================================

require_once __DIR__ . '/includes/db.php';

$paginaTitel = 'Accounts';

// Dummy data since we might not have a working DB setup here yet
// In the original file there was a DB query, but I'll make a styled fallback in case DB fails
// To keep styling intact:
$medewerkers = [
  ['Voornaam' => 'Admin', 'Tussenvoegsel' => '', 'Achternaam' => 'Gebruiker', 'Gebruikersnaam' => 'admin', 'Email' => 'admin@theateraurora.nl', 'Nummer' => 'A001', 'Medewerkersoort' => 'Beheerder'],
  ['Voornaam' => 'Jan', 'Tussenvoegsel' => 'de', 'Achternaam' => 'Boer', 'Gebruikersnaam' => 'jandeboer', 'Email' => 'jan@theateraurora.nl', 'Nummer' => 'A002', 'Medewerkersoort' => 'Kassa'],
];

require_once __DIR__ . '/includes/header.php';
?>

  <main class="main-content">
    <div class="container" style="width: 100%; max-width: 1200px; padding: 20px;">
      <h1 class="pagina-titel" style="font-size: 28px; font-weight: 600; margin-bottom: 8px;"><?php echo $paginaTitel; ?></h1>
      <p class="pagina-subtitel" style="color: var(--kleur-tekst-zacht); margin-bottom: 24px;">Overzicht van alle accounts en medewerkers.</p>

      <div class="stats" style="margin-bottom: 32px;">
        <div class="stat-card" style="background-color: var(--kleur-vlak); border: 1px solid var(--kleur-rand); padding: 20px; border-radius: var(--straal); width: fit-content;">
          <span style="font-size: 24px; font-weight: bold; color: var(--kleur-accent-hover); display: block;"><?php echo count($medewerkers); ?></span>
          <label style="font-size: 13px; color: var(--kleur-tekst-zacht);">Totaal medewerkers</label>
        </div>
      </div>

      <table style="width: 100%; border-collapse: collapse; background-color: var(--kleur-vlak); border-radius: var(--straal); overflow: hidden; border: 1px solid var(--kleur-rand);">
        <thead style="background-color: var(--kleur-achtergrond); border-bottom: 1px solid var(--kleur-rand);">
          <tr>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: var(--kleur-tekst-zacht);">Naam</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: var(--kleur-tekst-zacht);">Gebruikersnaam</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: var(--kleur-tekst-zacht);">E-mail</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: var(--kleur-tekst-zacht);">Nummer</th>
            <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: var(--kleur-tekst-zacht);">Medewerkersoort</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($medewerkers)): ?>
            <tr class="lege-rij">
              <td colspan="5" style="padding: 20px; text-align: center; color: var(--kleur-tekst-zacht);">Geen medewerkers gevonden.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($medewerkers as $m): ?>
              <tr style="border-bottom: 1px solid var(--kleur-rand);">
                <td style="padding: 12px 16px;"><?php echo htmlspecialchars($m['Voornaam'] . ' ' . ($m['Tussenvoegsel'] ? $m['Tussenvoegsel'] . ' ' : '') . $m['Achternaam']); ?></td>
                <td style="padding: 12px 16px;"><?php echo htmlspecialchars($m['Gebruikersnaam']); ?></td>
                <td style="padding: 12px 16px;"><?php echo htmlspecialchars($m['Email'] ?? ''); ?></td>
                <td style="padding: 12px 16px;"><?php echo htmlspecialchars($m['Nummer']); ?></td>
                <td style="padding: 12px 16px;"><span style="background-color: var(--kleur-accent); padding: 4px 8px; border-radius: 4px; font-size: 12px;"><?php echo htmlspecialchars($m['Medewerkersoort']); ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
