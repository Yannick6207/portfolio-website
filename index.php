<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$success = "";
if (isset($_GET['sent'])) {
    $success = "Je bericht is succesvol verzonden.";
}
$error = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pakket = $_POST['pakket'] ?? '';
    $message = trim($_POST['message'] ?? '');

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.strato.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yannick.huet@yannick25.nl';
        $mail->Password = "Max4Verstappen!?";
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom('yannick.huet@yannick25.nl', 'Portfolio Website');
        $mail->addAddress('yannick.huet@yannick25.nl');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);
        $mail->Subject = "Nieuw bericht van $name";
        $mail->Body = "Naam: $name\n";
        $mail->Body .= "E-mail: $email\n\n";
        $mail->Body .= "Pakketkeuze: " . ($pakket !== "" ? $pakket : "Geen pakket gekozen") . "\n\n";
        $mail->Body .= "Bericht:\n" . ($message !== "" ? $message : "Geen bericht ingevuld");

        $mail->send();
        header("Location: index.php?sent=1");
        exit;
    } catch (Exception $e) {
        $error = "Mail versturen mislukt. Fout: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yannick van Huet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="hero-boven">
        <header id="header">
            <nav class="navbar navbar-expand-lg navbar-light">
                <h2><a href="#header">Yannick.</a></h2>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#waarom">Waarom</a></li>
                        <li class="nav-item"><a class="nav-link" href="#overmij">Over Mij</a></li>
                        <li class="nav-item"><a class="nav-link" href="#projecten">Projecten</a></li>
                        <li class="nav-item"><a class="nav-link" href="#stappen">Stappen</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pakketten">Pakketten</a></li>
                        <li class="nav-item"><a class="nav-link" href="#voordelen">Voordelen</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="navigatie-responsief">
            <button class="menu-close" type="button" aria-label="Sluit menu">
                <span></span>
            </button>
            <h2>Yannick.</h2>
            <ul class="navigatie-menu">
                <li><a href="#waarom">Waarom</a></li>
                <li><a href="#overmij">Over Mij</a></li>
                <li><a href="#projecten">Projecten</a></li>
                <li><a href="#stappen">Stappen</a></li>
                <li><a href="#pakketten">Pakketten</a></li>
                <li><a href="#voordelen">Voordelen</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
        <div class="overlay"></div>
        <div class="hero-midden">
            <section class="sectie-links">
                <h1>Professionele websites die klanten opleveren</h1>
                <p>Ik help kleine bedrijven met een moderne, snelle website die zorgt voor meer aanvragen en een professionele uitstraling.</p>
                <div class="buttons">
                    <a href="#projecten">Bekijk mijn projecten</a>
                    <a href="#contact">Vraag vrijblijvend een website aan</a>
                </div>
                <div class="socials">
                    <a href="https://www.instagram.com/yannickvh2004/"><img src="afbeeldingen/instagram-logo-zonder-achtergrond.png" alt="Instagram"></a>
                    <a href="https://www.tiktok.com/@yannickvanhuet"><img src="afbeeldingen/tiktok-logo-zonder-achtergrond.png" alt="TikTok"></a>
                </div>
            </section>
            <section class="sectie-rechts">
                <img src="afbeeldingen/foto-website-yannick-zonder-achtergrond.png" alt="Yannick van Huet">
            </section>
        </div>
    </div>
    <section class="contentWaarom" id="waarom">
        <h2>Waarom kiezen voor <span>mij?</span></h2>
        <p>Een goede website is meer dan alleen design. Het moet snel, duidelijk en gericht zijn op het krijgen van klanten. Ik zorg ervoor dat jouw website niet alleen mooi is, maar ook echt werkt voor jouw bedrijf.</p>
    </section>
    <section class="contentOver" id="overmij">
        <h2>Over&nbsp;<span>Mij</span></h2>
        <div class="profile">
            <div class="profileImg">
                <img src="afbeeldingen/foto-website-yannick-zonder-achtergrond.png" alt="Yannick van Huet">
            </div>
            <h3>Jouw partner voor een sterke website</h3>
            <p>
                Mijn naam is Yannick en ik help ondernemers met het bouwen van moderne en gebruiksvriendelijke websites.
                <br><br>
                Ik focus niet alleen op hoe een website eruitziet, maar vooral op hoe deze presteert. Het doel is simpel: een website die vertrouwen uitstraalt en klanten oplevert.
                <br><br>
                Of je nu net begint of je huidige website wilt verbeteren, ik help je graag verder.
            </p>
        </div>
    </section>
    <section class="contentProjecten" id="projecten">
        <h2>Mijn&nbsp;<span>Projecten</span></h2>
        <p>Een paar voorbeelden van websites en concepten die laten zien hoe ik design en gebruiksvriendelijkheid combineer.</p>
        <div class="projecten w3-container">
            <div class="projectVoetbal project">
                <h3>Webapp voor <span>sportplatform</span></h3>
                <img src="afbeeldingen/icon-voetbal.png" alt="sportplatform icoon">
                <p>Een moderne en overzichtelijke website gericht op het aantrekken van nieuwe klanten en een professionele uitstraling.</p>
                <a href="http://voetbalapp.yannick25.nl/" target="_blank">Bekijk het project</a>
            </div>
            <div class="projectPokedex project">
                <h3>Pokedex <span>App</span></h3>
                <img src="afbeeldingen/pokemon.png" alt="demo webshop icoon">
                <p>Een moderne en overzichtelijke website gericht op het aantrekken van nieuwe klanten en een professionele uitstraling.</p>
                <a href="http://pokedex.yannick25.nl/" target="_blank">Bekijk het project</a>
            </div>
            <div class="projectContactPagina project">
                <h3>Website voor <span>contactgegevens</span></h3>
                <img src="afbeeldingen/frontendmentor.png" alt="dienstverlener icoon">
                <p>Een moderne en overzichtelijke website gericht op het aantrekken van nieuwe klanten en een professionele uitstraling.</p>
                <a href="http://contactpagina.yannick25.nl/" target="_blank">Bekijk het project</a>
            </div>
        </div>
    </section>
    <section class="contentStappen" id="stappen">
        <h2>De&nbsp;<span>Stappen</span></h2>
        <p>Ik maak het proces van het bouwen van een website eenvoudig en stressvrij. Hier is hoe het werkt:</p>
        <div class="stappen">
            <div class="stap">
                <h3>1. <span>Kennismaking</span></h3>
                <p>We beginnen met een gesprek om jouw wensen, doelen en doelgroep te begrijpen.</p>
            </div>
            <div class="stap">
                <h3>2. <span>Ontwerp</span></h3>
                <p>Ik maak een ontwerp dat past bij jouw merk en doelgroep, en zorg ervoor dat het er professioneel uitziet.</p>
            </div>
            <div class="stap">
                <h3>3. <span>Ontwikkeling</span></h3>
                <p>Ik bouw de website met moderne technologieen en zorg ervoor dat deze snel, veilig en gebruiksvriendelijk is.</p>
            </div>
            <div class="stap">
                <h3>4. <span>Lancering</span></h3>
                <p>Nadat je tevreden bent met het resultaat, lanceren we de website en zorg ik ervoor dat alles soepel verloopt.</p>
            </div>
        </div>
    </section>
    <section class="contentPakketten" id="pakketten">
        <h2>Mijn&nbsp;<span>Pakketten</span></h2>
        <p>Kies het pakket dat het beste past bij jouw bedrijf, wensen en budget.</p>
        <div class="pakketten">
            <div class="pakket">
                <h3>Starter <span>- &euro;349</span></h3>
                <p class="pakketIntro">Perfect voor kleine ondernemers die snel online willen zijn.</p>
                <ul>
                    <li>One-page website</li>
                    <li>Responsive design</li>
                    <li>Contactformulier</li>
                    <li>Basis styling</li>
                </ul>
                <p class="prijs">Snel en professioneel online</p>
            </div>
            <div class="pakket meestGekozen">
                <span class="badgePakket">Meest gekozen</span>
                <h3>Business <span>- &euro;599</span></h3>
                <p class="pakketIntro">Voor bedrijven die professioneel willen groeien.</p>
                <ul>
                    <li>Website tot 3 pagina's</li>
                    <li>Responsive design</li>
                    <li>Contactformulier</li>
                    <li>Social media koppelingen</li>
                    <li>Basis optimalisatie</li>
                </ul>
                <p class="prijs">Ideaal voor groeiende bedrijven</p>
            </div>
            <div class="pakket">
                <h3>Pro <span>- &euro;899</span></h3>
                <p class="pakketIntro">Voor ondernemers die alles uit hun website willen halen.</p>
                <ul>
                    <li>Website tot 5 pagina's</li>
                    <li>Uniek design op maat</li>
                    <li>Contactformulier</li>
                    <li>Basis SEO optimalisatie</li>
                    <li>Snelle laadtijd</li>
                </ul>
                <p class="prijs">Meer resultaat uit je website</p>
            </div>
            <div class="pakket">
                <h3>Custom <span>- vanaf &euro;1199</span></h3>
                <p class="pakketIntro">Voor grotere of unieke projecten.</p>
                <ul>
                    <li>Volledig maatwerk</li>
                    <li>Meerdere functies</li>
                    <li>Onderhoud en nazorg</li>
                </ul>
                <p class="prijs">Voor websites met extra mogelijkheden</p>
            </div>
        </div>
    </section>
    <section class="contentVoordelen" id="voordelen">
        <h2>Wat levert het je <span>op?</span></h2>
        <ul class="voordelenLijst">
            <li class="voordeel">Meer aanvragen via je website</li>
            <li class="voordeel">Professionele uitstraling</li>
            <li class="voordeel">Betere vindbaarheid</li>
            <li class="voordeel">Werkt perfect op mobiel</li>
        </ul>
    </section>
    <section class="contentContact" id="contact">
        <h2>Klaar voor jouw nieuwe <span>website?</span></h2>
        <p>Vraag vrijblijvend een website aan en ontdek wat ik voor jouw bedrijf kan betekenen.</p>
        <?php
            if ($success) {
                echo "<p style='color:green;'>$success</p>";
            }

            if ($error) {
                echo "<p style='color:red;'>$error</p>";
            }
        ?>
        <form action="#contact" method="POST">
            <input type="text" name="name" placeholder="Voornaam en achternaam" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <select name="pakket">
                <option value="" selected>Kies eventueel een pakket</option>
                <option value="Starter">Starter</option>
                <option value="Business">Business</option>
                <option value="Pro">Pro</option>
                <option value="Custom">Custom</option>
            </select>
            <textarea name="message" placeholder="Waarmee kan ik je helpen?"></textarea>
            <button type="submit" name="submit">Vraag vrijblijvend een website aan</button>
        </form>
    </section>
    <footer>
        <p>Copyright &copy; 2026 Yannick van Huet | All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        let body = document.querySelector("body");
        let nav = document.querySelector(".navbar");
        let header = document.querySelector("#header");
        let hamburger = document.querySelector(".navbar-toggler");
        let navMenu = document.querySelector(".navbar-collapse");
        let navResponsief = document.querySelector(".navigatie-responsief");
        let menuClose = document.querySelector(".menu-close");
        let navLinksResponsief = document.querySelectorAll(".navigatie-menu a");
        let overlay = document.querySelector(".overlay");
        let lastScrollY = window.scrollY;

        function closeMobileMenu() {
            body.classList.remove("is-active");
            body.classList.remove("no-scroll");
            body.style.overflowY = "auto";
            overlay.style.display = "none";
            nav.classList.remove("active");
            hamburger.classList.remove("active");
            navMenu.style.display = "none";
            navResponsief.classList.remove("show");
            updateHeaderOnScroll();
        }

        function openMobileMenu() {
            body.classList.add("is-active");
            body.classList.add("no-scroll");
            body.style.overflowY = "hidden";
            overlay.style.display = "block";
            nav.classList.add("active");
            hamburger.classList.add("active");
            navMenu.style.display = "none";
            navResponsief.classList.add("show");
            header.classList.add("header-fixed");
            header.classList.remove("header-hidden");
        }

        hamburger.addEventListener("click", function() {
            if (body.classList.contains("is-active")) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        const animatedItems = document.querySelectorAll(".project, .stap, .pakket, .voordeel");

        function showItemsOnScroll() {
            animatedItems.forEach(item => {
                const itemTop = item.getBoundingClientRect().top;
                const screenHeight = window.innerHeight;

                if (itemTop < screenHeight - 100) {
                    item.classList.add("show");
                }
            });
        }

        function updateHeaderOnScroll() {
            const currentScrollY = window.scrollY;
            const headerIsGone = currentScrollY > header.offsetHeight;

            if (body.classList.contains("is-active")) {
                header.classList.add("header-fixed");
                header.classList.remove("header-hidden");
                lastScrollY = currentScrollY;
                return;
            }

            if (!headerIsGone) {
                header.classList.remove("header-fixed");
                header.classList.remove("header-hidden");
                lastScrollY = currentScrollY;
                return;
            }

            header.classList.add("header-fixed");

            if (currentScrollY < lastScrollY) {
                header.classList.remove("header-hidden");
            } else if (currentScrollY > lastScrollY) {
                header.classList.add("header-hidden");
            }

            lastScrollY = currentScrollY;
        }

        function handleScroll() {
            showItemsOnScroll();
            updateHeaderOnScroll();
        }

        window.addEventListener("scroll", handleScroll);
        window.addEventListener("load", handleScroll);
        window.addEventListener("resize", updateHeaderOnScroll);

        navLinksResponsief.forEach(link => {
            link.addEventListener("click", function() {
                closeMobileMenu();
            });
        });

        menuClose.addEventListener("click", function() {
            closeMobileMenu();
        });

        overlay.addEventListener("click", function() {
            closeMobileMenu();
        });
    </script>
</body>
</html>
