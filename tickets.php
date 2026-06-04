<?php
$paginaTitel = 'Tickets – TheaterAurora';
require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container" style="width: 100%; max-width: 1200px; padding: 20px; margin: 0 auto;">
      <h1 class="pagina-titel" style="font-size: 28px; font-weight: 600; margin-bottom: 8px;">Jouw Tickets</h1>
      <p class="pagina-subtitel" style="color: var(--kleur-tekst-zacht); margin-bottom: 24px;">Hier zie je een overzicht van al je bestelde en gereserveerde tickets.</p>

      <div style="background-color: var(--kleur-vlak); border: 1px solid var(--kleur-rand); border-radius: var(--straal); padding: 40px; text-align: center;">
        <svg style="width: 48px; height: 48px; color: var(--kleur-tekst-zacht); margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
        <h3 style="font-size: 16px; margin-bottom: 8px; color: var(--kleur-tekst);">Geen tickets gevonden</h3>
        <p style="color: var(--kleur-tekst-zacht); font-size: 14px; margin-bottom: 20px;">Je hebt momenteel geen actieve tickets. Ga naar de voorstellingen om tickets te bestellen.</p>
        <a href="voorstellingen.php" class="knop knop-primair" style="display: inline-block; padding: 9px 22px; border-radius: var(--straal); font-size: 13px; font-weight: 500; text-decoration: none; cursor: pointer; transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease; background-color: var(--kleur-accent); border: 1px solid var(--kleur-accent-hover); color: var(--kleur-tekst);">Bekijk Voorstellingen</a>
      </div>
    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>
