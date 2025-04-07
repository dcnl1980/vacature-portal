# Vacatureportal

Een compacte vacatureportal met overzicht, detailpagina's en een sollicitatiemogelijkheid, gebouwd met PHP, SQLite en native JavaScript.

## Functies

-   Overzichtspagina met vacatures.
-   Zoekfunctie op functie/bedrijf en locatie.
-   Detailpagina per vacature.
-   Sollicitatieformulier met client-side validatie.
-   Opslaan van sollicitaties in de database.

## Vereisten

-   **Standaard:** PHP 7.4+, SQLite & PDO extensies, webserver (bv. Apache, Nginx).
-   **Docker:** Docker & Docker Compose.

## Installatie

### Standaard

1.  Clone dit project.
2.  Configureer je webserver om de `public` map als webroot te gebruiken.
3.  Initialiseer de database:
    ```bash
    php setup_database.php
    ```

### Docker

1.  Clone dit project.
2.  Start de container:
    ```bash
    docker-compose up -d
    ```
3.  De applicatie is beschikbaar op [http://localhost:8080](http://localhost:8080).

## Projectstructuur

```
/vacatures
├── app/              # Applicatielogica
│   ├── Core/         # Kernfunctionaliteit (bv. DB connectie)
│   ├── Models/       # Datamodellen (Vacature, Sollicitatie)
│   └── Views/        # Templates en weergave (incl. /components)
├── public/           # Publiek toegankelijke bestanden (webroot)
│   ├── assets/       # CSS, JavaScript
│   ├── index.php     # Routering
│   └── sollicitatie.php # Sollicitatieverwerking
├── data/             # SQLite database bestand
├── uploads/          # Geüploade bestanden (bv. CV's in /cv)
├── docker/           # Docker configuratie
├── setup_database.php # Database initialisatiescript
├── Dockerfile        # Docker image definitie
└── docker-compose.yml # Docker Compose configuratie
```

## Gebruik

1.  Open de website.
2.  Bekijk en filter vacatures.
3.  Klik op "Bekijk vacature" voor details.
4.  Klik op "Solliciteer" om het formulier in te vullen.

## Docker Beheer

-   **Starten:** `docker-compose up -d`
-   **Stoppen:** `docker-compose down`
-   **Logs bekijken:** `docker-compose logs -f`
-   **Herbouwen:** `docker-compose build --no-cache`

## Beveiliging

Het project implementeert diverse beveiligingsmaatregelen:

-   **SQL Injectie:** Bescherming via prepared statements (PDO).
-   **Input Validatie:** Zowel client-side (JavaScript) als server-side (PHP).
-   **CSRF:** Bescherming met tokens in formulieren.
-   **Bestandsuploads:** Validatie op bestandstype en -grootte.
-   **Output Escaping:** Gebruik van `htmlspecialchars` om XSS te voorkomen. 