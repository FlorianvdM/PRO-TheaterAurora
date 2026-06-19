<?php
session_start();
// ============================================
// includes/header.php
// TheaterAurora – Globale header include
// ============================================

require_once __DIR__ . '/db.php';

if ($pdo === null) {
    $foutMelding = $dbFout ?? 'Er is een fout opgetreden.';
    ?><!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fout – TheaterAurora</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>" />
</head>
<body>
  <header class="site-header">
    <div class="header-logo">TheaterAurora</div>
  </header>
  <main class="error-container">
    <div class="error-inhoud">
      <h1 class="error-titel">Database niet bereikbaar</h1>
      <p class="error-bericht"><?= htmlspecialchars($foutMelding) ?></p>
    </div>
  </main>
</body>
</html>
<?php
    exit;
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($paginaTitel ?? 'TheaterAurora') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>" />
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">

  <header class="site-header">
    <div class="header-logo"><a href="index.php">TheaterAurora</a></div>
    <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="header-nav" id="header-nav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="voorstellingen.php">Voorstellingen</a></li>
        <li><a href="tickets.php">Tickets</a></li>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Admin'): ?>
          <li><a href="accounts.php">Accounts</a></li>
          <li><a href="medewerker.php">Medewerkers</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['Admin', 'Medewerker'])): ?>
          <li><a href="meldingen.php">Meldingen</a></li>
        <?php endif; ?>
        <li>
          <?php if (isset($_SESSION['gebruiker_id'])): ?>
            <a href="logout.php">Uitloggen (<?= htmlspecialchars($_SESSION['gebruikersnaam']) ?>)</a>
          <?php else: ?>
            <a href="login.php">Inloggen</a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </header>
