// ============================================
// script.js – Alle scripts
// ============================================

// Meldingen automatisch verbergen na 2 seconden
document.addEventListener('DOMContentLoaded', () => {
  const meldingenBalken = document.querySelectorAll('.alert');

  meldingenBalken.forEach(balk => {
    setTimeout(() => {
      balk.classList.add('alert--verdwijnt');
      balk.addEventListener('transitionend', () => balk.remove());
    }, 2000);
  });
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
});
