<?php
// medewerker/nieuwe-melding.php
// Pagina: Nieuwe melding aanmaken
// Layout gebaseerd op wireframe — Theater Aurora Bordeauxrood stijl

$paginaTitel = 'Nieuwe melding aanmaken';
include '../PRO-TheaterAurora/includes/header.php';

// Verwerk formulier na POST
$melding = null;
$fouten  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie   = trim($_POST['categorie'] ?? '');
    $prioriteit  = trim($_POST['prioriteit'] ?? '');
    $titel       = trim($_POST['titel'] ?? '');
    $beschrijving = trim($_POST['beschrijving'] ?? '');

    // Validatie
    if (empty($categorie)) {
        $fouten['categorie'] = 'Kies een categorie.';
    }
    if (empty($prioriteit)) {
        $fouten['prioriteit'] = 'Kies een prioriteit.';
    }
    if (empty($titel)) {
        $fouten['titel'] = 'Vul een titel in voor de melding.';
    }

    if (empty($fouten)) {
        // TODO: sla melding op in database
        $melding = 'succes';
    } else {
        $melding = 'fout';
    }
}
?>

<main>
    <h1 class="pagina-titel">Nieuwe melding aanmaken</h1>

    <?php if ($melding === 'succes'): ?>
        <div class="melding-banner succes">
            De melding is succesvol opgeslagen.
        </div>
    <?php elseif ($melding === 'fout'): ?>
        <div class="melding-banner fout">
            Er zijn fouten gevonden. Controleer de velden hieronder.
        </div>
    <?php endif; ?>

    <div class="formulier-kaart">
        <form method="POST" action="nieuwe-melding.php" novalidate>

            <!-- Rij 1: Categorie + Prioriteit -->
            <div class="formulier-rij">

                <div class="formulier-groep <?= isset($fouten['categorie']) ? 'veld-fout' : '' ?>">
                    <label for="categorie">
                        Categorie <span class="verplicht">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select id="categorie" name="categorie">
                            <option value="" disabled <?= empty($_POST['categorie']) ? 'selected' : '' ?>>
                                Kies een categorie
                            </option>
                            <option value="voorstelling" <?= ($_POST['categorie'] ?? '') === 'voorstelling' ? 'selected' : '' ?>>
                                Voorstelling
                            </option>
                            <option value="tickets" <?= ($_POST['categorie'] ?? '') === 'tickets' ? 'selected' : '' ?>>
                                Tickets
                            </option>
                            <option value="service" <?= ($_POST['categorie'] ?? '') === 'service' ? 'selected' : '' ?>>
                                Service
                            </option>
                        </select>
                    </div>
                    <span class="veld-hint">Opties: Voorstelling / Tickets / Service</span>
                    <?php if (isset($fouten['categorie'])): ?>
                        <span class="fout-tekst"><?= htmlspecialchars($fouten['categorie']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="formulier-groep <?= isset($fouten['prioriteit']) ? 'veld-fout' : '' ?>">
                    <label for="prioriteit">
                        Prioriteit <span class="verplicht">*</span>
                    </label>
                    <div class="select-wrapper">
                        <select id="prioriteit" name="prioriteit">
                            <option value="" disabled <?= empty($_POST['prioriteit']) ? 'selected' : '' ?>>
                                Kies een prioriteit
                            </option>
                            <option value="laag" <?= ($_POST['prioriteit'] ?? '') === 'laag' ? 'selected' : '' ?>>
                                Laag
                            </option>
                            <option value="normaal" <?= ($_POST['prioriteit'] ?? 'normaal') === 'normaal' ? 'selected' : '' ?>>
                                Normaal
                            </option>
                            <option value="hoog" <?= ($_POST['prioriteit'] ?? '') === 'hoog' ? 'selected' : '' ?>>
                                Hoog
                            </option>
                        </select>
                    </div>
                    <span class="veld-hint">Opties: Laag / Normaal / Hoog</span>
                    <?php if (isset($fouten['prioriteit'])): ?>
                        <span class="fout-tekst"><?= htmlspecialchars($fouten['prioriteit']) ?></span>
                    <?php endif; ?>
                </div>

            </div><!-- /formulier-rij -->

            <!-- Rij 2: Titel melding (volledige breedte) -->
            <div class="formulier-rij">
                <div class="formulier-groep vol-breedte <?= isset($fouten['titel']) ? 'veld-fout' : '' ?>">
                    <label for="titel">
                        Titel melding <span class="verplicht">*</span>
                    </label>
                    <input
                        type="text"
                        id="titel"
                        name="titel"
                        placeholder="Korte omschrijving van de melding"
                        value="<?= htmlspecialchars($_POST['titel'] ?? '') ?>"
                        maxlength="150"
                    >
                    <?php if (isset($fouten['titel'])): ?>
                        <span class="fout-tekst"><?= htmlspecialchars($fouten['titel']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rij 3: Beschrijving (volledige breedte) -->
            <div class="formulier-rij">
                <div class="formulier-groep vol-breedte">
                    <label for="beschrijving">Beschrijving</label>
                    <textarea
                        id="beschrijving"
                        name="beschrijving"
                        placeholder="Uitgebreide omschrijving (optioneel) …"
                        rows="6"
                    ><?= htmlspecialchars($_POST['beschrijving'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Knoppen -->
            <div class="knop-groep">
                <a href="meldingen.php" class="btn btn-secundair">Annuleren</a>
                <button type="submit" class="btn btn-primair">Melding opslaan</button>
            </div>

        </form>
    </div><!-- /formulier-kaart -->
</main>

<?php include '../PRO-TheaterAurora/includes/footer.php'; ?>