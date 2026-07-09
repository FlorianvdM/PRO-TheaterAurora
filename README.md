## Database-platform-notities
- Standaard (Docker Compose): PHP draait in de app-container, DB_HOST=db werkt automatisch.
- PHP lokaal draaien (buiten Docker, bv. via WAMP): maak een .env aan op basis van .env.example en zet DB_HOST=127.0.0.1:3308.
- De MySQL-container exposeert poort 3308 (niet 3306) om conflicten met een lokaal geïnstalleerde MySQL/MariaDB te voorkomen.
