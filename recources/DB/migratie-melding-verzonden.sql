-- migratie_melding_verzonden.sql
-- Voegt bij-houden van "verzonden naar bezoekers" toe aan een bestaande database.
-- Eenmalig uitvoeren op je lokale theater_aurora database.

USE theater_aurora;

ALTER TABLE Melding
    ADD COLUMN Verzonden BIT NOT NULL DEFAULT 0 AFTER Bericht,
    ADD COLUMN VerzondenOp DATETIME(6) NULL AFTER Verzonden;