// ============================================
// script.js – Alle scripts
// ============================================

// Hamburger menu toggle
document.addEventListener('DOMContentLoaded', () => {
  const hamburger = document.getElementById('hamburger');
  const nav = document.getElementById('header-nav');

  if (hamburger && nav) {
    hamburger.addEventListener('click', (e) => {
      e.stopPropagation();
      nav.classList.toggle('open');
      hamburger.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', nav.classList.contains('open'));
    });

    document.addEventListener('click', (e) => {
      if (!nav.contains(e.target) && !hamburger.contains(e.target) && nav.classList.contains('open')) {
        nav.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });
  }
});

// Meldingen tab filtering
document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.tab');
  const meldingen = document.querySelectorAll('.melding-item');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('actief'));
      tab.classList.add('actief');

      const filter = tab.dataset.filter;

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
