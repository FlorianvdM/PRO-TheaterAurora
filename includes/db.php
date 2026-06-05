<?php

$dbHost = 'localhost';
$dbNaam = 'theater_aurora';
$dbGebruiker = 'root';
$dbWachtwoord = 'root';

try {
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
