<?php
// ============================================
// index.php
// TheaterAurora – Homepage
// ============================================

$paginaTitel = 'Home – TheaterAurora';
// We hebben hier geen aparte CSS nodig omdat de algemene hero-stijlen in style.css staan
require_once 'includes/header.php';
?>

  <main class="main-content">
    <section class="hero-sectie">
      <h1 class="hero-titel">Welkom bij TheaterAurora</h1>
      <p class="hero-ondertitel">Ontdek de mooiste voorstellingen en reserveer je tickets.</p>
      <div class="hero-knoppen">
        <a href="voorstellingen.php" class="knop knop-primair">Bekijk Voorstellingen</a>
        <a href="tickets.php" class="knop knop-secundair">Mijn Tickets</a>
      </div>
    </section>
  </main>

<?php require_once 'includes/footer.php'; ?>
