<?php
// ============================================
// index.php
// TheaterAurora – Homepage
// ============================================

$paginaTitel = 'Theater Aurora – Waar toneelstukken echt tot leven komen';
$bodyClass = 'homepagina-bg'; // Hero achtergrond op homepage
require_once 'includes/header.php';
?>

  <main class="main-content">
    <section class="hero-sectie"> <!-- Hero banner -->
      <h1 class="hero-titel">Theater Aurora</h1>
      <p class="hero-ondertitel">Waar toneelstukken echt tot leven komen</p>
      <div class="hero-knoppen">
        <a href="tickets.php" class="knop knop-primair">Koop je tickets</a>
        <a href="voorstellingen.php" class="knop knop-secundair">Zie voorstellingen</a>
      </div>
    </section>
  </main>

<?php require_once 'includes/footer.php'; ?>
