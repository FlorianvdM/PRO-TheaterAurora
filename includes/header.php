<?php
// ============================================
// includes/header.php
// TheaterAurora – Globale header include
// ============================================

require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($paginaTitel ?? 'TheaterAurora') ?></title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/meldingen.css" />
  <?php if (!empty($paginaCss)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($paginaCss) ?>" />
  <?php endif; ?>
  <style>
    /* Hamburger menu */
    .hamburger {
      display: none;
      flex-direction: column;
      justify-content: center;
      gap: 5px;
      background: none;
      border: 1px solid var(--kleur-tekst);
      border-radius: var(--straal);
      padding: 8px 10px;
      cursor: pointer;
    }
    .hamburger span {
      display: block;
      width: 22px;
      height: 2px;
      background-color: var(--kleur-tekst);
      border-radius: 2px;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    .header-nav a { color: var(--kleur-tekst); text-decoration: none; }
    @media (max-width: 768px) {
      .hamburger { display: flex; }
      .site-header { position: relative; }
      .header-nav {
        position: absolute;
        top: 100%; left: 0; right: 0;
        border: none; border-radius: 0; padding: 0;
        background-color: var(--kleur-achtergrond);
        border-bottom: 1px solid var(--kleur-rand);
        max-height: 0; overflow: hidden;
        transition: max-height 0.3s ease;
      }
      .header-nav.open { max-height: 300px; }
      .header-nav ul {
        flex-direction: column; gap: 0;
        padding: 10px 20px;
      }
      .header-nav li { border-bottom: 1px solid var(--kleur-rand); }
      .header-nav li:last-child { border-bottom: none; }
      .header-nav a {
        display: block; padding: 12px 0;
        color: var(--kleur-tekst);
        text-decoration: none;
      }
    }
  </style>
</head>
<body>

  <header class="site-header">
    <div class="header-logo"><a href="index.php" style="color: inherit; text-decoration: none;">TheaterAurora</a></div>
    <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <nav class="header-nav" id="header-nav">
      <ul style="display: flex; gap: 15px; list-style: none; margin: 0; padding: 0;">
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
