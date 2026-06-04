// ============================================
// assets/js/meldingen.js
// TheaterAurora – Meldingen tab filtering
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.tab');
  const meldingen = document.querySelectorAll('.melding-item');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Actieve tab bijwerken
      tabs.forEach(t => t.classList.remove('actief'));
      tab.classList.add('actief');

      const filter = tab.dataset.filter;

      // Meldingen filteren
      meldingen.forEach(melding => {
        if (filter === 'alle' || melding.dataset.type === filter) {
          melding.classList.remove('verborgen');
        } else {
          melding.classList.add('verborgen');
        }
      });
    });
  });
});
