<?php
// ============================================
// setup.php
// TheaterAurora – Database setup & seeding
// ============================================

require_once __DIR__ . '/includes/db.php';

try {
    $sqlBestand = __DIR__ . '/recources/DB/database.sql';
    $sql = file_get_contents($sqlBestand);
    $pdo->exec($sql);
    echo "Tabellen succesvol aangemaakt.<br>";

    $seedBestand = __DIR__ . '/recources/DB/seed.sql';
    $seed = file_get_contents($seedBestand);
    $pdo->exec($seed);
    echo "Seed data succesvol ingevoegd.<br>";

    echo "<br><a href='medewerker.php'>Ga naar medewerker overzicht</a>";
} catch (PDOException $e) {
    exit('Fout bij het uitvoeren van SQL: ' . $e->getMessage());
}
