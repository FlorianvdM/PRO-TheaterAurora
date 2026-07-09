<?php
// tickets.php – Ticket overzicht
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';

$paginaTitel = 'Tickets – TheaterAurora';

$kanTicketsBeheren = isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['Admin', 'Medewerker']);

$tickets = [];
if ($pdo !== null) {
    $sql = 'SELECT t.Id, t.Nummer, t.Barcode, t.Datum, t.Tijd, t.Status, t.Isactief,
                   v.Naam AS VoorstellingNaam,
                   CONCAT(g.Voornaam, IF(g.Tussenvoegsel IS NOT NULL, CONCAT(" ", g.Tussenvoegsel), ""), " ", g.Achternaam) AS BezoekerNaam,
                   p.Tarief
            FROM Ticket t
            INNER JOIN Voorstelling v ON v.Id = t.VoorstellingId
            INNER JOIN Bezoeker b ON b.Id = t.BezoekerId
            INNER JOIN Gebruiker g ON g.Id = b.GebruikerId
            INNER JOIN Prijs p ON p.Id = t.PrijsId
            WHERE t.Isactief = 1
            ORDER BY t.Datum DESC, t.Tijd DESC';
    $tickets = $pdo->query($sql)->fetchAll();
}

$succes = isset($_GET['succes']) && $_GET['succes'] == '1';
$geannuleerd = isset($_GET['geannuleerd']) && $_GET['geannuleerd'] == '1';
$fout = isset($_GET['fout']) && $_GET['fout'] == '1';

function ticketStatusClass($status)
{
    $status = strtolower($status);
    if ($status === 'geannuleerd') {
        return 'status-badge--geannuleerd';
    }
    if ($status === 'gereserveerd') {
        return 'status-badge--gereserveerd';
    }
    return 'status-badge--actief';
}

require_once 'includes/header.php';
?>

  <main class="main-content">
    <div class="container">

      <div class="pagina-header">
        <h1 class="sectie-titel">Tickets</h1>
        <?php if ($kanTicketsBeheren): ?>
          <a href="ticket-toevoegen.php" class="knop knop-primair">+ Nieuw ticket</a>
        <?php endif; ?>
      </div>

      <?php if ($succes): ?>
        <div class="alert alert-success">Ticket succesvol opgeslagen</div>
      <?php endif; ?>
      <?php if ($geannuleerd): ?>
        <div class="alert alert-success">Ticket succesvol geannuleerd</div>
      <?php endif; ?>
      <?php if ($fout): ?>
        <div class="alert alert-danger">De ticketactie kon niet worden uitgevoerd</div>
      <?php endif; ?>

      <?php if (!empty($tickets)): ?>
        <div class="table-container">
          <table class="tickets-tabel">
            <thead>
              <tr>
                <th>Nr.</th>
                <th>Voorstelling</th>
                <th>Bezoeker</th>
                <th>Datum</th>
                <th>Tijd</th>
                <th>Status</th>
                <th>Tarief</th>
                <th>Barcode</th>
                <?php if ($kanTicketsBeheren): ?>
                  <th>Acties</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tickets as $t): ?>
                <tr class="<?php echo $t['Status'] === 'Geannuleerd' ? 'ticket-geannuleerd' : ''; ?>">
                  <td data-label="Nr."><?php echo htmlspecialchars($t['Nummer']); ?></td>
                  <td data-label="Voorstelling"><?php echo htmlspecialchars($t['VoorstellingNaam']); ?></td>
                  <td data-label="Bezoeker"><?php echo htmlspecialchars($t['BezoekerNaam']); ?></td>
                  <td data-label="Datum"><?php echo date('d-m-Y', strtotime($t['Datum'])); ?></td>
                  <td data-label="Tijd"><?php echo date('H:i', strtotime($t['Tijd'])); ?></td>
                  <td data-label="Status">
                    <span class="status-badge <?php echo ticketStatusClass($t['Status']); ?>">
                      <?php echo htmlspecialchars($t['Status']); ?>
                    </span>
                  </td>
                  <td data-label="Tarief">&euro;<?php echo number_format($t['Tarief'], 2, ',', '.'); ?></td>
                  <td data-label="Barcode"><?php echo htmlspecialchars($t['Barcode']); ?></td>
                  <?php if ($kanTicketsBeheren): ?>
                    <td data-label="Acties" class="cel-acties">
                      <div class="cel-acties-inner">
                        <?php if ($t['Status'] !== 'Geannuleerd'): ?>
                          <!-- PBI 395: annuleer alleen tickets die nog niet geannuleerd zijn. -->
                          <a
                            href="ticket-annuleren.php?id=<?php echo urlencode($t['Id']); ?>"
                            class="knop-klein knop-klein--annuleer"
                          >Annuleer</a>
                        <?php else: ?>
                          <span class="knop-klein knop-klein--uitgeschakeld">Geannuleerd</span>
                        <?php endif; ?>
                      </div>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="tickets-lege-staat">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
          <h3 class="tickets-lege-titel">Geen tickets gevonden</h3>
          <p class="tickets-lege-tekst">Er zijn nog geen tickets geregistreerd.</p>
          <?php if ($kanTicketsBeheren): ?>
            <a href="ticket-toevoegen.php" class="knop knop-primair">Nieuw ticket toevoegen</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>
