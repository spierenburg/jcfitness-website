<?php
// === CONFIGURATIE ===
$recaptcha_secret = 'HIER_JE_SECRET_KEY';
$ontvanger = 'info@jcfitness.nl';
$onderwerp_prefix = 'JC Fitness';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
if (empty($recaptcha_response)) {
    header('Location: contact.html?status=error');
    exit;
}

$verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($recaptcha_secret) . '&response=' . urlencode($recaptcha_response));
$result = json_decode($verify, true);

if (!$result['success'] || $result['score'] < 0.5) {
    header('Location: contact.html?status=error');
    exit;
}

$naam = htmlspecialchars(trim($_POST['naam'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$telefoon = htmlspecialchars(trim($_POST['telefoon'] ?? ''), ENT_QUOTES, 'UTF-8');
$interesse = htmlspecialchars(trim($_POST['interesse'] ?? ''), ENT_QUOTES, 'UTF-8');
$bericht = htmlspecialchars(trim($_POST['bericht'] ?? ''), ENT_QUOTES, 'UTF-8');

if (empty($naam) || empty($email) || empty($bericht)) {
    header('Location: contact.html?status=error');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.html?status=error');
    exit;
}

$onderwerp = "$onderwerp_prefix — Bericht van $naam";

$body = "Nieuw bericht via de website:\n\n";
$body .= "Naam: $naam\n";
$body .= "E-mailadres: $email\n";
$body .= "Telefoon: $telefoon\n";
$body .= "Interesse: $interesse\n";
$body .= "\nBericht:\n$bericht\n";

$headers = "From: noreply@jcfitness.nl\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$verzonden = mail($ontvanger, $onderwerp, $body, $headers);

if ($verzonden) {
    header('Location: bedankt.html');
} else {
    header('Location: contact.html?status=error');
}
exit;
