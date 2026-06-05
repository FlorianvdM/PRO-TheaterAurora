<?php
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
  <link rel="stylesheet" href="assets/css/style.css" />
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
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

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
        <li><a href="accounts.php">Accounts</a></li>
        <li><a href="meldingen.php">Meldingen</a></li>
      </ul>
    </nav>
  </header>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var hamburger = document.getElementById('hamburger');
      var nav = document.getElementById('header-nav');
      if (hamburger && nav) {
        hamburger.addEventListener('click', function() {
          nav.classList.toggle('open');
          hamburger.classList.toggle('open');
          hamburger.setAttribute('aria-expanded', nav.classList.contains('open'));
        });
      }
    });
  </script>
