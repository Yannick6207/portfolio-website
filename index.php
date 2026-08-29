<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function escapeEmailHtml($value)
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function getSmtpPassword()
{
    $environmentPassword = getenv('PASSWORD');
    if ($environmentPassword !== false && $environmentPassword !== '') {
        return $environmentPassword;
    }

    $environmentFile = __DIR__ . '/.env';
    $environment = is_readable($environmentFile) ? parse_ini_file($environmentFile) : false;

    return is_array($environment) && isset($environment['PASSWORD'])
        ? (string) $environment['PASSWORD']
        : '';
}

function configureMailer(PHPMailer $mail, $smtpPassword)
{
    $mail->isSMTP();
    $mail->Host = 'smtp.strato.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yannick.huet@yannick25.nl';
    $mail->Password = $smtpPassword;
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->setFrom('yannick.huet@yannick25.nl', 'YN Webdesign');
    $mail->isHTML(true);
    $mail->addEmbeddedImage(
        __DIR__ . '/afbeeldingen/ynwebdesign-logo.png',
        'ynwebdesign-logo',
        'ynwebdesign-logo.png',
        'base64',
        'image/png'
    );
}

function createEmailLayout($preheader, $content)
{
    return '<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YN Webdesign</title>
    <style>
        @media only screen and (max-width: 620px) {
            .email-shell { width: 100% !important; }
            .email-padding { padding: 28px 20px !important; }
            .email-title { font-size: 26px !important; line-height: 34px !important; }
            .email-button { display: block !important; text-align: center !important; }
            .email-logo { width: 180px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#eef4f8;font-family:Arial,Helvetica,sans-serif;color:#14243a;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">' . $preheader . '</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background-color:#eef4f8;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" class="email-shell" style="width:600px;max-width:600px;background-color:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 28px rgba(9,30,55,.10);">
                    <tr>
                        <td align="center" style="padding:20px 32px;background-color:#071b33;border-bottom:4px solid #18c7e8;">
                            <img src="cid:ynwebdesign-logo" width="210" class="email-logo" alt="YN Webdesign" style="display:block;width:210px;max-width:100%;height:auto;border:0;outline:none;text-decoration:none;">
                        </td>
                    </tr>
                    <tr>
                        <td class="email-padding" style="padding:38px 40px;">' . $content . '</td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px;background-color:#071b33;color:#b9cada;font-size:12px;line-height:19px;text-align:center;">
                            YN Webdesign &nbsp;&bull;&nbsp; yannick25.nl
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
}

$success = "";
if (isset($_GET['sent'])) {
    $success = "Bedankt! Je aanvraag is succesvol verstuurd. Je ontvangt binnen enkele minuten een bevestiging per e-mail.";
}
$error = "";

if (isset($_POST['submit'])) {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $pakket = trim((string) ($_POST['pakket'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    $allowedPackages = ['', 'Starter', 'Business', 'Pro', 'Custom'];

    if ($name === '' || preg_match('/[\r\n]/', $name)) {
        $error = 'Vul een geldige naam in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $email)) {
        $error = 'Vul een geldig e-mailadres in.';
    } elseif (!in_array($pakket, $allowedPackages, true)) {
        $error = 'Kies een geldig pakket.';
    } else {
        $pakketDisplay = $pakket !== '' ? $pakket : 'Geen pakket gekozen';
        $messageDisplay = $message !== '' ? $message : 'Geen bericht ingevuld';

        $safeName = escapeEmailHtml($name);
        $safeEmail = escapeEmailHtml($email);
        $safePackage = escapeEmailHtml($pakketDisplay);
        $safeMessage = nl2br(escapeEmailHtml($messageDisplay), false);
        $replyUrl = 'mailto:' . escapeEmailHtml($email);

        $detailRows = '
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:12px 14px;background:#f2f7fa;border-bottom:1px solid #dbe7ee;width:130px;font-size:13px;font-weight:700;color:#496277;">Naam</td><td style="padding:12px 14px;border-bottom:1px solid #dbe7ee;font-size:15px;">' . $safeName . '</td></tr>
                <tr><td style="padding:12px 14px;background:#f2f7fa;border-bottom:1px solid #dbe7ee;font-size:13px;font-weight:700;color:#496277;">E-mailadres</td><td style="padding:12px 14px;border-bottom:1px solid #dbe7ee;font-size:15px;"><a href="mailto:' . $safeEmail . '" style="color:#087f9b;">' . $safeEmail . '</a></td></tr>
                <tr><td style="padding:12px 14px;background:#f2f7fa;font-size:13px;font-weight:700;color:#496277;">Pakket</td><td style="padding:12px 14px;font-size:15px;">' . $safePackage . '</td></tr>
            </table>';

        $internalContent = '
            <p style="margin:0 0 8px;color:#18aeca;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Nieuwe websiteaanvraag</p>
            <h1 class="email-title" style="margin:0 0 24px;color:#071b33;font-size:30px;line-height:38px;">Nieuwe aanvraag van ' . $safeName . '</h1>
            ' . $detailRows . '
            <h2 style="margin:28px 0 10px;color:#071b33;font-size:18px;">Bericht</h2>
            <div style="padding:18px;background-color:#f2f7fa;border-left:4px solid #18c7e8;border-radius:4px;font-size:15px;line-height:24px;overflow-wrap:anywhere;">' . $safeMessage . '</div>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin-top:28px;"><tr><td bgcolor="#18c7e8" style="border-radius:6px;"><a class="email-button" href="' . $replyUrl . '" style="display:inline-block;padding:14px 22px;color:#071b33;text-decoration:none;font-size:15px;font-weight:700;">Beantwoord ' . $safeName . '</a></td></tr></table>
            <p style="margin:28px 0 0;color:#6a7d8e;font-size:12px;line-height:19px;">Deze aanvraag is ontvangen via yannick25.nl.</p>';

        $customerContent = '
            <p style="margin:0 0 8px;color:#18aeca;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Bedankt voor je aanvraag</p>
            <h1 class="email-title" style="margin:0 0 18px;color:#071b33;font-size:30px;line-height:38px;">Hoi ' . $safeName . ',</h1>
            <p style="margin:0 0 24px;color:#344b60;font-size:16px;line-height:26px;">Je aanvraag is goed ontvangen. Ik bekijk je wensen en neem zo snel mogelijk contact met je op.</p>
            <p style="margin:0 0 6px;color:#496277;font-size:13px;font-weight:700;">Gekozen pakket</p>
            <p style="margin:0 0 22px;color:#071b33;font-size:16px;">' . $safePackage . '</p>
            <p style="margin:0 0 8px;color:#496277;font-size:13px;font-weight:700;">Jouw bericht</p>
            <div style="padding:18px;background-color:#f2f7fa;border-left:4px solid #18c7e8;border-radius:4px;font-size:15px;line-height:24px;overflow-wrap:anywhere;">' . $safeMessage . '</div>
            <p style="margin:28px 0 0;color:#344b60;font-size:15px;line-height:23px;">Met vriendelijke groet,<br><strong style="color:#071b33;">Yannick van Huet</strong><br>YN Webdesign</p>';

        $mail = new PHPMailer(true);

        try {
            $smtpPassword = getSmtpPassword();
            if ($smtpPassword === '') {
                throw new Exception('SMTP-wachtwoord ontbreekt.');
            }

            configureMailer($mail, $smtpPassword);
            $mail->addAddress('yannick.huet@yannick25.nl');
            $mail->addReplyTo($email, $name);
            $mail->Subject = 'Nieuwe websiteaanvraag – ' . $name;
            $mail->Body = createEmailLayout('Nieuwe websiteaanvraag van ' . $safeName, $internalContent);
            $mail->AltBody = "Nieuwe websiteaanvraag\n\nNaam: {$name}\nE-mailadres: {$email}\nPakket: {$pakketDisplay}\n\nBericht:\n{$messageDisplay}\n\nOntvangen via yannick25.nl.";
            $mail->send();

            $confirmationMail = new PHPMailer(true);
            configureMailer($confirmationMail, $smtpPassword);
            $confirmationMail->addAddress($email, $name);
            $confirmationMail->addReplyTo('yannick.huet@yannick25.nl', 'YN Webdesign');
            $confirmationMail->Subject = 'Bedankt voor je aanvraag bij YN Webdesign';
            $confirmationMail->Body = createEmailLayout('Je aanvraag bij YN Webdesign is goed ontvangen.', $customerContent);
            $confirmationMail->AltBody = "Hoi {$name},\n\nJe aanvraag is goed ontvangen. Ik bekijk je wensen en neem zo snel mogelijk contact met je op.\n\nGekozen pakket: {$pakketDisplay}\n\nJouw bericht:\n{$messageDisplay}\n\nMet vriendelijke groet,\n\nYannick van Huet\nYN Webdesign";
            $confirmationMail->send();

            header('Location: index.php?sent=1#contact');
            exit;
        } catch (Exception $e) {
            error_log('Contactformulier mailfout: ' . $e->getMessage() . ' / ' . $mail->ErrorInfo);
            $error = 'Mail versturen is helaas mislukt. Probeer het later opnieuw.';
        }
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
                <h2 class="site-logo">
                    <a href="index.php" aria-label="YNWebDesign - naar de homepage">
                        <img src="afbeeldingen/ynwebdesign-logo.png" alt="YNWebDesign-logo">
                    </a>
                </h2>
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
            <h2 class="site-logo">
                <a href="index.php" aria-label="YNWebDesign - naar de homepage">
                    <img src="afbeeldingen/ynwebdesign-logo.png" alt="YNWebDesign-logo">
                </a>
            </h2>
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
        <p>Een paar voorbeelden van websites die laten zien hoe ik design en gebruiksvriendelijkheid combineer.</p>
        <div class="projecten w3-container">
            <article class="projectNova project">
                <h3><span>NOVA</span></h3>
                <p class="projectType">Conceptwebsite · Artiestenmanagement</p>
                <div class="projectVisual">
                    <img class="projectScreenshot" src="afbeeldingen/nova-project.png" alt="Homepage van de NOVA-conceptwebsite" loading="lazy">
                    <img class="projectLogo" src="afbeeldingen/nova-logo.svg" alt="NOVA Artist Management-logo">
                </div>
                <p class="projectDescription">Ik ontwierp en ontwikkelde een krachtige, responsive website met een artiestenoverzicht, duidelijke diensten en conversiegerichte contactmomenten.</p>
                <ul class="projectTech" aria-label="Gebruikte technieken">
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>JavaScript</li>
                    <li>PHP</li>
                </ul>
                <a class="projectCta" href="http://nova.yannick25.nl/" target="_blank" rel="noopener noreferrer">Bekijk het project</a>
            </article>
            <article class="projectFitFuel project">
                <h3><span>FitFuel</span></h3>
                <p class="projectType">Conceptwebsite · Maaltijdservice</p>
                <div class="projectVisual">
                    <img class="projectScreenshot" src="afbeeldingen/fitfuel-project.png" alt="Homepage van de FitFuel-conceptwebsite" loading="lazy">
                    <img class="projectLogo" src="afbeeldingen/fitfuel-logo.svg" alt="FitFuel-logo">
                </div>
                <p class="projectDescription">Ik ontwierp en ontwikkelde een moderne, responsive website waarmee gebruikers maaltijden ontdekken, voedingsinformatie bekijken en eenvoudig een passend aanbod vinden.</p>
                <ul class="projectTech" aria-label="Gebruikte technieken">
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>JavaScript</li>
                    <li>PHP</li>
                </ul>
                <a class="projectCta" href="http://fitfuel.yannick25.nl/" target="_blank" rel="noopener noreferrer">Bekijk het project</a>
            </article>
            <article class="projectHorecaWaarheid project">
                <h3><span>HorecaWaarheid</span></h3>
                <p class="projectType">Website &amp; webapp · Horeca-inzicht</p>
                <div class="projectVisual">
                    <img class="projectScreenshot" src="afbeeldingen/horecawaarheid-project.png" alt="Homepage van HorecaWaarheid" loading="lazy">
                    <img class="projectLogo" src="afbeeldingen/horecawaarheid-logo-met-achtergrond.png" alt="HorecaWaarheid-logo">
                </div>
                <p class="projectDescription">Ik ontwierp en ontwikkelde een responsive platform dat financiële inzichten, contractanalyse en praktisch advies samenbrengt in een helder dashboard voor horecaondernemers.</p>
                <ul class="projectTech" aria-label="Gebruikte technieken">
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>JavaScript</li>
                    <li>PHP</li>
                </ul>
                <a class="projectCta" href="https://horecawaarheid.nl/" target="_blank" rel="noopener noreferrer">Bekijk het project</a>
            </article>
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
        <p>&copy; 2026 YN Webdesign. Alle rechten voorbehouden.</p>
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
