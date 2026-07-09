<?php
// ticket-annuleren.php - Ticket als geannuleerd markeren (Admin/Medewerker)
require_once __DIR__ . '/includes/db.php';

session_start();
require_once __DIR__ . '/includes/toegang.php';
vereistToegang(['Admin', 'Medewerker']);

$paginaTitel = 'Ticket annuleren';
$error = null;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ticket = null;

if ($id <= 0) {
    header('Location: tickets.php?fout=1');
    exit;
}

// Ticketgegevens ophalen zodat de medewerker eerst kan controleren wat wordt geannuleerd.
if ($pdo !== null) {
    try {
        $stmt = $pdo->prepare('
            SELECT t.Id, t.Nummer, t.Datum, t.Tijd, t.Status, t.Opmerking,
                   v.Naam AS VoorstellingNaam,
                   CONCAT(g.Voornaam, IF(g.Tussenvoegsel IS NOT NULL, CONCAT(" ", g.Tussenvoegsel), ""), " ", g.Achternaam) AS BezoekerNaam
            FROM Ticket t
            INNER JOIN Voorstelling v ON v.Id = t.VoorstellingId
            INNER JOIN Bezoeker b ON b.Id = t.BezoekerId
            INNER JOIN Gebruiker g ON g.Id = b.GebruikerId
            WHERE t.Id = :id AND t.Isactief = 1
        ');
        $stmt->execute([':id' => $id]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            header('Location: tickets.php?fout=1');
            exit;
        }
    } catch (PDOException $e) {
        $error = 'Ticket kan niet worden geladen.';
    }
}

// Annuleren gebeurt pas na expliciete bevestiging via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opmerking = trim($_POST['opmerking'] ?? '');

    if (($ticket['Status'] ?? '') === 'Geannuleerd') {
        $error = 'De annulering kan niet worden uitgevoerd omdat dit ticket al geannuleerd is.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            // Status wijzigen in plaats van verwijderen, zodat het ticket zichtbaar blijft in het overzicht.
            $stmt = $pdo->prepare('
                UPDATE Ticket
                SET Status = "Geannuleerd",
                    Opmerking = COALESCE(NULLIF(:opmerking, ""), Opmerking),
                    Datumgewijzigd = NOW(6)
                WHERE Id = :id AND Isactief = 1 AND Status <> "Geannuleerd"
            ');
            $stmt->execute([
                ':opmerking' => $opmerking,
                ':id' => $id,
            ]);

            // Geen gewijzigde rij betekent dat het ticket niet meer annuleerbaar was.
            if ($stmt->rowCount() === 0) {
                $error = 'De annulering kan niet worden uitgevoerd.';
            } else {
                header('Location: tickets.php?geannuleerd=1');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'De annulering kan niet worden uitgevoerd.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
  <div class="container">
    <h1>Ticket annuleren</h1>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($ticket): ?>
      <section class="ticket-detail-card">
        <h2 class="ticket-detail-titel">Controleer het ticket voordat u annuleert</h2>
        <dl class="ticket-details">
          <div>
            <dt>Ticketnummer</dt>
            <dd><?php echo htmlspecialchars($ticket['Nummer']); ?></dd>
          </div>
          <div>
            <dt>Voorstelling</dt>
            <dd><?php echo htmlspecialchars($ticket['VoorstellingNaam']); ?></dd>
          </div>
          <div>
            <dt>Bezoeker</dt>
            <dd><?php echo htmlspecialchars($ticket['BezoekerNaam']); ?></dd>
          </div>
          <div>
            <dt>Datum en tijd</dt>
            <dd><?php echo date('d-m-Y', strtotime($ticket['Datum'])) . ' ' . date('H:i', strtotime($ticket['Tijd'])); ?></dd>
          </div>
          <div>
            <dt>Status</dt>
            <dd><?php echo htmlspecialchars($ticket['Status']); ?></dd>
          </div>
        </dl>

        <?php if ($ticket['Status'] !== 'Geannuleerd'): ?>
          <form method="post" action="ticket-annuleren.php?id=<?php echo urlencode($id); ?>" class="ticket-annuleer-form">
            <div class="form-group">
              <label for="opmerking">Reden of opmerking</label>
              <input type="text" id="opmerking" name="opmerking" value="<?php echo htmlspecialchars($_POST['opmerking'] ?? ''); ?>">
            </div>
            <div class="form-acties">
              <button type="submit" class="knop knop-primair">Ticket annuleren</button>
              <a href="tickets.php" class="knop knop-secundair">Terug</a>
            </div>
          </form>
        <?php else: ?>
          <div class="form-acties">
            <a href="tickets.php" class="knop knop-secundair">Terug naar tickets</a>
          </div>
        <?php endif; ?>
      </section>
    <?php endif; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
