USE theater_aurora;

INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd) VALUES
('Jan', 'van', 'Dijk', 'jvdijk', 'wachtwoord123', 0, 1, 'Directeur', NOW(), NOW()),
('Marieke', NULL, 'de Vries', 'mdevries', 'wachtwoord123', 0, 1, 'Administratief medewerker', NOW(), NOW()),
('Peter', NULL, 'Bakker', 'pbakker', 'wachtwoord123', 0, 1, 'Technisch medewerker', NOW(), NOW()),
('Sandra', 'de', 'Wit', 'sdewit', 'wachtwoord123', 0, 1, 'Horeca medewerker', NOW(), NOW()),
('Ahmed', NULL, 'Al-Hassan', 'aalhassan', 'wachtwoord123', 0, 1, 'Schoonmaak medewerker', NOW(), NOW()),
('Lisa', 'van der', 'Meer', 'lvandermeer', 'wachtwoord123', 0, 1, 'Bezoeker', NOW(), NOW());

INSERT INTO Contact (GebruikerId, Email, Mobiel, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd) VALUES
(1, 'jan.vandijk@theateraurora.nl', '0612345678', 1, NULL, NOW(), NOW()),
(2, 'marieke.devries@theateraurora.nl', '0612345679', 1, NULL, NOW(), NOW()),
(3, 'peter.bakker@theateraurora.nl', '0612345680', 1, NULL, NOW(), NOW()),
(4, 'sandra.dewit@theateraurora.nl', '0612345681', 1, NULL, NOW(), NOW()),
(5, 'ahmed.alhassan@theateraurora.nl', '0612345682', 1, NULL, NOW(), NOW()),
(6, 'lisa.vandermeer@example.com', '0612345683', 1, NULL, NOW(), NOW());

INSERT INTO Rol (GebruikerId, Naam, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd) VALUES
(1, 'Admin', 1, NULL, NOW(), NOW()),
(2, 'Medewerker', 1, NULL, NOW(), NOW()),
(3, 'Medewerker', 1, NULL, NOW(), NOW()),
(4, 'Medewerker', 1, NULL, NOW(), NOW()),
(5, 'Medewerker', 1, NULL, NOW(), NOW()),
(6, 'Bezoeker', 1, NULL, NOW(), NOW());

INSERT INTO Medewerker (GebruikerId, Nummer, Medewerkersoort, Isactief, Opmerking, Datumaangemaakt, Datumgewijzigd) VALUES
(1, 1001, 'Directie', 1, 'Algehele leiding', NOW(), NOW()),
(2, 1002, 'Administratie', 1, 'Financiën en planning', NOW(), NOW()),
(3, 1003, 'Techniek', 1, 'Geluid en licht', NOW(), NOW()),
(4, 1004, 'Horeca', 1, 'Foyer en catering', NOW(), NOW()),
(5, 1005, 'Schoonmaak', 1, 'Onderhoud en schoonmaak', NOW(), NOW());
