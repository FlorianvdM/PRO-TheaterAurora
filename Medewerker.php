<?php

require_once __DIR__ . '/includes/db.php';

$paginaTitel = 'Medewerkers';

$stmt = $pdo->query(
    'SELECT m.*, g.Voornaam, g.Tussenvoegsel, g.Achternaam, g.Gebruikersnaam, c.Email
     FROM Medewerker m
     INNER JOIN Gebruiker g ON g.Id = m.GebruikerId
     LEFT JOIN Contact c ON c.GebruikerId = g.Id
     ORDER BY m.Datumaangemaakt DESC'
);
$medewerkers = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $paginaTitel; ?> - Theater Aurora</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #fff; color: #222; padding: 2rem; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { font-size: 1.75rem; font-weight: 600; margin-bottom: 0.25rem; }
        .subtitle { color: #666; margin-bottom: 2rem; }
        .stats { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: #f5f5f5; border-radius: 8px; padding: 1rem 1.5rem; }
        .stat-card span { font-size: 1.5rem; font-weight: 700; display: block; }
        .stat-card label { font-size: 0.85rem; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 0.75rem 0.5rem; border-bottom: 1px solid #eee; }
        th { font-weight: 600; color: #555; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .lege-rij td { color: #999; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $paginaTitel; ?></h1>
        <p class="subtitle">Overzicht van alle medewerkers.</p>

        <div class="stats">
            <div class="stat-card">
                <span><?php echo count($medewerkers); ?></span>
                <label>Totaal medewerkers</label>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Gebruikersnaam</th>
                    <th>E-mail</th>
                    <th>Nummer</th>
                    <th>Medewerkersoort</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($medewerkers)): ?>
                    <tr class="lege-rij">
                        <td colspan="5">Geen medewerkers gevonden.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($medewerkers as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['Voornaam'] . ' ' . ($m['Tussenvoegsel'] ? $m['Tussenvoegsel'] . ' ' : '') . $m['Achternaam']); ?></td>
                            <td><?php echo htmlspecialchars($m['Gebruikersnaam']); ?></td>
                            <td><?php echo htmlspecialchars($m['Email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($m['Nummer']); ?></td>
                            <td><?php echo htmlspecialchars($m['Medewerkersoort']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
