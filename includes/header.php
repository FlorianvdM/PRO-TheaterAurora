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
  <link rel="stylesheet" href="assets/css/style.css" />
  <?php if (!empty($paginaCss)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($paginaCss) ?>" />
  <?php endif; ?>
</head>
<body>

  <header class="site-header">
    <div class="header-logo"><a href="index.php" style="color: inherit; text-decoration: none;">TheaterAurora</a></div>
    <nav class="header-nav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="voorstellingen.php">Voorstellingen</a></li>
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="accounts.php">Accounts</a></li>
        <li><a href="meldingen.php">Meldingen</a></li>
      </ul>
    </nav>
  </header>
