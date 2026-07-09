<?php
// feedback.php - Feedback ontvangen en bekijken
require_once __DIR__ . '/includes/db.php';

session_start();

$paginaTitel = 'Feedback - TheaterAurora';
$error = null;
$succes = isset($_GET['succes']) && $_GET['succes'] == '1';
$isMedewerker = isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['Admin', 'Medewerker']);
$feedbackItems = [];
$ingelogdeBezoeker = null;

function maakFeedbackTabelIndienNodig($pdo)
{
    // Deze PBI is later toegevoegd, daarom wordt de tabel ook op bestaande databases aangemaakt.
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS Feedback (
            Id INT AUTO_INCREMENT PRIMARY KEY,
            BezoekerId INT,
            Nummer MEDIUMINT NOT NULL UNIQUE,
            Naam VARCHAR(120),
            Onderwerp VARCHAR(100),
            Bericht TEXT NOT NULL,
            Isactief BIT NOT NULL,
            Opmerking VARCHAR(250),
            Datumaangemaakt DATETIME(6) NOT NULL,
            Datumgewijzigd DATETIME(6) NOT NULL,
            FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id)
        )
    ');
}

if ($pdo !== null) {
    try {
        maakFeedbackTabelIndienNodig($pdo);

        if (isset($_SESSION['gebruiker_id'])) {
            $stmt = $pdo->prepare('
                SELECT b.Id,
                       CONCAT(g.Voornaam, IF(g.Tussenvoegsel IS NOT NULL, CONCAT(" ", g.Tussenvoegsel), ""), " ", g.Achternaam) AS Naam
                FROM Bezoeker b
                INNER JOIN Gebruiker g ON g.Id = b.GebruikerId
                WHERE b.GebruikerId = :gebruikerId AND b.Isactief = 1
            ');
            $stmt->execute([':gebruikerId' => $_SESSION['gebruiker_id']]);
            $ingelogdeBezoeker = $stmt->fetch();
        }
    } catch (PDOException $e) {
        $error = 'Feedback kan momenteel niet worden geladen.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isMedewerker) {
    $naam = trim($_POST['naam'] ?? '');
    $onderwerp = trim($_POST['onderwerp'] ?? '');
    $bericht = trim($_POST['bericht'] ?? '');

    if ($bericht === '') {
        $error = 'Vul feedback in voordat u verzendt.';
    } elseif ($pdo === null) {
        $error = 'DataBase niet verbonden';
    } else {
        try {
            $stmt = $pdo->query('SELECT COALESCE(MAX(Nummer), 1000) + 1 FROM Feedback');
            $nummer = $stmt->fetchColumn();

            $stmt = $pdo->prepare('
                INSERT INTO Feedback (BezoekerId, Nummer, Naam, Onderwerp, Bericht, Isactief, Datumaangemaakt, Datumgewijzigd)
                VALUES (:bezoekerId, :nummer, :naam, :onderwerp, :bericht, 1, NOW(6), NOW(6))
            ');
            $stmt->execute([
                ':bezoekerId' => $ingelogdeBezoeker['Id'] ?? null,
                ':nummer' => $nummer,
                ':naam' => $ingelogdeBezoeker['Naam'] ?? ($naam ?: null),
                ':onderwerp' => $onderwerp ?: null,
                ':bericht' => $bericht,
            ]);

            header('Location: feedback.php?succes=1');
            exit;
        } catch (PDOException $e) {
            $error = 'De feedback kon niet worden opgeslagen.';
        }
    }
}

if ($isMedewerker && $pdo !== null && $error === null) {
    try {
        $stmt = $pdo->query('
            SELECT f.Nummer, f.Naam, f.Onderwerp, f.Bericht, f.Datumaangemaakt,
                   COALESCE(
                       NULLIF(f.Naam, ""),
                       CONCAT(g.Voornaam, IF(g.Tussenvoegsel IS NOT NULL, CONCAT(" ", g.Tussenvoegsel), ""), " ", g.Achternaam),
                       "Onbekende bezoeker"
                   ) AS BezoekerNaam
            FROM Feedback f
            LEFT JOIN Bezoeker b ON b.Id = f.BezoekerId
            LEFT JOIN Gebruiker g ON g.Id = b.GebruikerId
            WHERE f.Isactief = 1
            ORDER BY f.Datumaangemaakt DESC
        ');
        $feedbackItems = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Feedback kan momenteel niet worden geladen.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
  <div class="container">
    <div class="pagina-header">
      <div>
        <h1 class="sectie-titel">Feedback</h1>
        <p class="pagina-subtitel">
          <?php echo $isMedewerker ? 'Ontvangen feedback van bezoekers.' : 'Laat ons weten hoe uw bezoek is verlopen.'; ?>
        </p>
      </div>
    </div>

    <?php if ($succes): ?>
      <div class="alert alert-success">Feedback succesvol opgeslagen</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($isMedewerker): ?>
      <?php if (!empty($feedbackItems)): ?>
        <section class="feedback-lijst" aria-label="Ontvangen feedback">
          <?php foreach ($feedbackItems as $feedback): ?>
            <article class="feedback-kaart">
              <div class="feedback-kop">
                <div>
                  <h2 class="feedback-titel">
                    <?php echo htmlspecialchars($feedback['Onderwerp'] ?: 'Feedback #' . $feedback['Nummer']); ?>
                  </h2>
                  <p class="feedback-meta">
                    <span><?php echo htmlspecialchars($feedback['BezoekerNaam']); ?></span>
                    <span><?php echo date('d-m-Y H:i', strtotime($feedback['Datumaangemaakt'])); ?></span>
                  </p>
                </div>
              </div>
              <p class="feedback-bericht"><?php echo nl2br(htmlspecialchars($feedback['Bericht'])); ?></p>
            </article>
          <?php endforeach; ?>
        </section>
      <?php else: ?>
        <div class="tickets-lege-staat feedback-lege-staat">
          <h3 class="tickets-lege-titel">Geen feedback gevonden</h3>
          <p class="tickets-lege-tekst">Er is momenteel geen feedback beschikbaar.</p>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <form method="post" action="feedback.php" class="form-container feedback-formulier">
        <?php if (!$ingelogdeBezoeker): ?>
          <div class="form-group">
            <label for="naam">Naam</label>
            <input type="text" id="naam" name="naam" value="<?php echo htmlspecialchars($_POST['naam'] ?? ''); ?>">
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="onderwerp">Onderwerp</label>
          <input type="text" id="onderwerp" name="onderwerp" value="<?php echo htmlspecialchars($_POST['onderwerp'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label for="bericht">Feedback *</label>
          <textarea id="bericht" name="bericht" rows="6" required><?php echo htmlspecialchars($_POST['bericht'] ?? ''); ?></textarea>
        </div>
        <div class="form-acties">
          <button type="submit" class="knop knop-primair">Feedback verzenden</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
