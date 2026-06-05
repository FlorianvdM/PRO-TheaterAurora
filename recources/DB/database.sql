CREATE DATABASE IF NOT EXISTS theater_aurora;
USE theater_aurora;

CREATE TABLE Gebruiker (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Voornaam VARCHAR(50) NOT NULL,
    Tussenvoegsel VARCHAR(10),
    Achternaam VARCHAR(50) NOT NULL,
    Gebruikersnaam VARCHAR(100) NOT NULL,
    Wachtwoord VARCHAR(255) NOT NULL,
    IsIngelogd BIT NOT NULL,
    Ingelogd DATETIME,
    Uitgelogd DATETIME,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL
);

CREATE TABLE Rol (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT NOT NULL,
    Naam VARCHAR(100) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id)
);

CREATE TABLE Contact (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Mobiel VARCHAR(20) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id)
);

CREATE TABLE Medewerker (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT NOT NULL,
    Nummer MEDIUMINT NOT NULL UNIQUE,
    Medewerkersoort VARCHAR(20) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id)
);

CREATE TABLE Bezoeker (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    GebruikerId INT NOT NULL,
    Relatienummer MEDIUMINT NOT NULL UNIQUE,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (GebruikerId) REFERENCES Gebruiker(Id)
);

CREATE TABLE Prijs (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Tarief DECIMAL(5,2) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL
);

CREATE TABLE Voorstelling (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    MedewerkerId INT NOT NULL,
    Naam VARCHAR(100) NOT NULL,
    Beschrijving TEXT,
    Datum DATE NOT NULL,
    Tijd TIME NOT NULL,
    MaxAantalTickets INT NOT NULL,
    Beschikbaarheid VARCHAR(50) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
);

CREATE TABLE Ticket (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    BezoekerId INT NOT NULL,
    VoorstellingId INT NOT NULL,
    PrijsId INT NOT NULL,
    Nummer MEDIUMINT NOT NULL UNIQUE,
    Barcode VARCHAR(20) NOT NULL UNIQUE,
    Datum DATE NOT NULL,
    Tijd TIME NOT NULL,
    Status VARCHAR(20) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id),
    FOREIGN KEY (VoorstellingId) REFERENCES Voorstelling(Id),
    FOREIGN KEY (PrijsId) REFERENCES Prijs(Id)
);

CREATE TABLE Melding (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    BezoekerId INT,
    MedewerkerId INT,
    Nummer MEDIUMINT NOT NULL UNIQUE,
    Type VARCHAR(20) NOT NULL,
    Bericht VARCHAR(250) NOT NULL,
    Isactief BIT NOT NULL,
    Opmerking VARCHAR(250),
    Datumaangemaakt DATETIME(6) NOT NULL,
    Datumgewijzigd DATETIME(6) NOT NULL,
    FOREIGN KEY (BezoekerId) REFERENCES Bezoeker(Id),
    FOREIGN KEY (MedewerkerId) REFERENCES Medewerker(Id)
);
