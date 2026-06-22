<?php
// db.php – Database verbinding (PDO)
$dbHost = getenv('DB_HOST') ?: 'localhost:3307';
$dbNaam = getenv('DB_NAME') ?: 'theater_aurora';
$dbGebruiker = getenv('DB_USER') ?: 'root';
$dbWachtwoord = getenv('DB_PASSWORD') ?: '';

try { // PDO verbinding opzetten met exception mode
    $pdo = new PDO(
        "mysql:host={$dbHost};charset=utf8mb4",
        $dbGebruiker,
        $dbWachtwoord,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbNaam}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbNaam}`");
} catch (PDOException $e) {
    $pdo = null;
    $dbFout = 'Databaseverbinding mislukt. Probeer het later opnieuw.';
}
