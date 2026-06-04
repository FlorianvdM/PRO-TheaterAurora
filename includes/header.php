<?php
// ============================================
// includes/header.php
// TheaterAurora – Globale header include
// ============================================
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($paginaTitel ?? 'TheaterAurora') ?></title>
  <link rel="stylesheet" href="assets/css/meldingen.css" /> <!-- LET OP WELK STYLE BESTAND IS GELINKED -->
</head>
<body>

  <header class="site-header">
    <div class="header-logo"><a href="index.php" style="color: inherit; text-decoration: none;">TheaterAurora</a></div>
    <nav class="header-nav">
      <ul style="display: flex; gap: 15px; list-style: none; margin: 0; padding: 0;">
        <li><a href="index.php">Home</a></li>
        <li><a href="voorstellingen.php">Voorstellingen</a></li>
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="accounts.php">Accounts</a></li>
        <li><a href="meldingen.php">Meldingen</a></li>
      </ul>
    </nav>
  </header>
