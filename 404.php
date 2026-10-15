<?php
http_response_code(404);
header('X-Robots-Tag: noindex, follow', true);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina niet gevonden | YN Webdesign</title>
    <meta name="description" content="Deze pagina bestaat niet of is verplaatst. Ga terug naar YN Webdesign of bekijk de mogelijkheden voor een professionele website.">
    <meta name="robots" content="noindex, follow">
    <meta name="theme-color" content="#0A1A2F">
    <link rel="icon" href="/afbeeldingen/ynwebdesign-logo.webp" type="image/webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
</head>
<body class="service-page">
    <header class="page-header">
        <nav class="page-nav" aria-label="Hoofdnavigatie">
            <a class="page-logo" href="/" aria-label="YN Webdesign - naar de homepage">
                <img src="/afbeeldingen/ynwebdesign-logo.webp" width="384" height="256" alt="YN Webdesign">
            </a>
            <div class="page-nav-links">
                <a href="/website-laten-maken.php">Website laten maken</a>
                <a href="/#projecten">Projecten</a>
                <a class="nav-cta" href="/#contact">Contact</a>
            </div>
        </nav>
    </header>
    <main class="error-page">
        <div class="error-page-content">
            <p class="error-code" aria-hidden="true">404</p>
            <h1>Deze pagina is niet gevonden</h1>
            <p>De pagina kan zijn verplaatst of de link klopt niet. Via de homepage vind je de webdesignprojecten, websitepakketten en contactmogelijkheden.</p>
            <a href="/">Terug naar de homepage</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2026 YN Webdesign. Alle rechten voorbehouden.</p>
        <nav aria-label="Voettekstnavigatie">
            <a href="/website-laten-maken.php">Website laten maken</a>
            <a href="mailto:info@ynwebdesign.nl">info@ynwebdesign.nl</a>
        </nav>
    </footer>
</body>
</html>
