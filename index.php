<?php
// ============================================
// index.php
// TheaterAurora – Homepage
// ============================================

$paginaTitel = 'Theater Aurora – Waar toneelstukken echt tot leven komen';

require_once 'includes/header.php';
?>

  <main class="main-content">
    <section class="hero-sectie">
      <h1 class="hero-titel">Theater Aurora</h1>
      <p class="hero-ondertitel">Waar toneelstukken echt tot leven komen</p>
      <div class="hero-knoppen">
        <a href="reservering.php" class="knop knop-primair">Maak een reservering</a>
        <a href="voorstellingen.php" class="knop knop-secundair">Zie voorstellingen</a>
      </div>
    </section>
  </main>

<?php require_once 'includes/footer.php'; ?>
