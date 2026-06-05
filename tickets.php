<?php
$paginaTitel = 'Tickets – TheaterAurora';
require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="tickets-container">
      <h1 class="tickets-titel">Jouw Tickets</h1>
      <p class="tickets-subtitel">Hier zie je een overzicht van al je bestelde en gereserveerde tickets.</p>

      <div class="tickets-lege-staat">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
        <h3 class="tickets-lege-titel">Geen tickets gevonden</h3>
        <p class="tickets-lege-tekst">Je hebt momenteel geen actieve tickets. Ga naar de voorstellingen om tickets te bestellen.</p>
        <a href="voorstellingen.php" class="knop knop-primair">Bekijk Voorstellingen</a>
      </div>
    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>
