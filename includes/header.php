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
  <link rel="stylesheet" href="assets/css/style.css" /> <!-- LET OP WELK STYLE BESTAND IS GELINKED -->
</head>
<body>

  <header class="site-header">
    <div class="header-logo">Logo</div>
    <nav class="header-nav">Nav Links</nav>
  </header>
