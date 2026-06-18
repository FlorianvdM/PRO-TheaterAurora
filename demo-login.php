<?php
// demo-login.php – Presentatie tool: één-klik login per rol
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gebruikersnaam']) && $pdo !== null) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $stmt = $pdo->prepare('SELECT Id, Gebruikersnaam, Wachtwoord, Isactief FROM Gebruiker WHERE Gebruikersnaam = :g');
    $stmt->execute([':g' => $gebruikersnaam]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && $gebruiker['Isactief'] && password_verify('wachtwoord123', $gebruiker['Wachtwoord'])) {
        $stmt = $pdo->prepare('UPDATE Gebruiker SET IsIngelogd = 1, Ingelogd = NOW() WHERE Id = :id');
        $stmt->execute([':id' => $gebruiker['Id']]);

        $stmt = $pdo->prepare('SELECT Naam FROM Rol WHERE GebruikerId = :id AND Isactief = 1');
        $stmt->execute([':id' => $gebruiker['Id']]);
        $rol = $stmt->fetchColumn();

        $_SESSION['gebruiker_id'] = $gebruiker['Id'];
        $_SESSION['gebruikersnaam'] = $gebruiker['Gebruikersnaam'];
        $_SESSION['rol'] = $rol ?: 'Bezoeker';

        header('Location: index.php');
        exit;
    }
}

$stmt = $pdo->query("
    SELECT g.Id, g.Gebruikersnaam, g.Voornaam, g.Tussenvoegsel, g.Achternaam, r.Naam AS Rol
    FROM Gebruiker g
    LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.Isactief = 1
    WHERE g.Gebruikersnaam IN ('jvdijk', 'mdevries', 'lvandermeer')
    ORDER BY FIELD(g.Gebruikersnaam, 'jvdijk', 'mdevries', 'lvandermeer')
");
$gebruikers = $stmt->fetchAll();

$paginaTitel = 'Demo – Kies een rol';
require_once __DIR__ . '/includes/header.php';
?>
<main class="main-content">
  <div class="container">
    <h1>Demo inloggen</h1>
    <p class="pagina-subtitel">Klik op een rol om direct in te loggen voor de presentatie.</p>

    <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 20px;">
      <?php foreach ($gebruikers as $g): ?>
        <form method="post" action="">
          <input type="hidden" name="gebruikersnaam" value="<?= htmlspecialchars($g['Gebruikersnaam']) ?>">
          <button type="submit" style="
            background: var(--kleur-vlak);
            border: 2px solid var(--kleur-rand);
            border-radius: var(--straal);
            padding: 20px 28px;
            cursor: pointer;
            min-width: 200px;
            text-align: center;
            transition: border-color 0.2s, background-color 0.2s;
            font-family: var(--lettertype);
          "
          onmouseover="this.style.borderColor='var(--kleur-accent)'; this.style.backgroundColor='var(--kleur-accent)'"
          onmouseout="this.style.borderColor='var(--kleur-rand)'; this.style.backgroundColor='var(--kleur-vlak)'">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--kleur-tekst-zacht); margin-bottom: 6px;">
              <?= htmlspecialchars($g['Rol']) ?>
            </div>
            <div style="font-size: 16px; font-weight: 700; color: var(--kleur-tekst);">
              <?= htmlspecialchars($g['Voornaam'] . ' ' . ($g['Tussenvoegsel'] ? $g['Tussenvoegsel'] . ' ' : '') . $g['Achternaam']) ?>
            </div>
            <div style="font-size: 12px; color: var(--kleur-tekst-zacht); margin-top: 4px;">
              @<?= htmlspecialchars($g['Gebruikersnaam']) ?>
            </div>
          </button>
        </form>
      <?php endforeach; ?>
    </div>

    <p style="margin-top: 30px; color: var(--kleur-tekst-zacht); font-size: 13px;">
      Of ga naar <a href="login.php" style="color: var(--kleur-accent);">het normale inlogformulier</a>.
    </p>
  </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
