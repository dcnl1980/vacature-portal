<?php
/**
 * Sollicitatie.php - Verwerkt het sollicitatieformulier via AJAX
 * 
 * Dit bestand verwerkt de ingediende sollicitaties en slaat deze op in de database,
 * retourneert een JSON response.
 * 
 * @author: Chris van Steenbergen
 */

session_start();

// Helper functie om JSON response te sturen en script te stoppen
function sendJsonResponse($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Controleer of er een POST-request is gedaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Ongeldige request methode.');
}

require_once __DIR__ . '/../app/Models/Sollicitatie.php';
require_once __DIR__ . '/../app/Models/Vacature.php';

// Initialiseer de modellen
$sollicitatieModel = new Sollicitatie();
$vacatureModel = new Vacature();

// Haal de vacature ID op en valideer
$vacatureId = isset($_POST['vacature_id']) ? (int)$_POST['vacature_id'] : 0;

// Controleer of er een geldige vacature ID is meegegeven
if ($vacatureId <= 0) {
    sendJsonResponse(false, 'Ongeldige vacature ID.');
}

// Controleer of de vacature bestaat
$vacature = $vacatureModel->getVacatureById($vacatureId);
if (!$vacature) {
    sendJsonResponse(false, 'De opgegeven vacature bestaat niet.');
}

// CSRF-bescherming
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
    sendJsonResponse(false, 'Ongeldige sessie of CSRF token. Probeer het formulier opnieuw te laden.');
}

// Valideer formuliergegevens
$naam = isset($_POST['naam']) ? trim($_POST['naam']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$motivatie = isset($_POST['motivatie']) ? trim($_POST['motivatie']) : '';

// Server-side validatie
$errors = [];

if (strlen($naam) < 2) {
    $errors[] = 'Voer een geldige naam in (minimaal 2 tekens).';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Voer een geldig e-mailadres in.';
}

if (strlen($motivatie) < 50) {
    $errors[] = 'Voer een motivatie in van minimaal 50 tekens.';
}

// CV Validatie
if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    switch ($_FILES['cv']['error'] ?? UPLOAD_ERR_NO_FILE) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errors[] = 'Het CV-bestand is te groot.';
            break;
        case UPLOAD_ERR_NO_FILE:
            $errors[] = 'Upload een CV-bestand.';
            break;
        default:
            $errors[] = 'Er is een fout opgetreden bij het uploaden van het CV.';
            break;
    }
} else {
    $cvFile = $_FILES['cv'];
    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($cvFile['type'], $allowedTypes)) {
        $errors[] = 'Ongeldig bestandsformaat voor CV (alleen PDF of Word toegestaan).';
    }
    if ($cvFile['size'] > $maxSize) {
        $errors[] = 'Het CV-bestand mag maximaal 2MB zijn.';
    }
}


// Zijn er validatiefouten?
if (!empty($errors)) {
    $errorHtml = '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
    sendJsonResponse(false, 'Validatiefouten:<br>' . $errorHtml);
}

// Sla de sollicitatie op
$result = $sollicitatieModel->opslaan($vacatureId, $naam, $email, $_FILES['cv'], $motivatie);

// Controleer of het opslaan is gelukt
if ($result === true) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
    sendJsonResponse(true, 'Je sollicitatie is succesvol ontvangen. We streven ernaar binnen 3 werkdagen contact met je op te nemen.');
} else {
    error_log('Fout bij opslaan sollicitatie: ' . $result);
    sendJsonResponse(false, 'Er is een technische fout opgetreden bij het verwerken van je sollicitatie. Probeer het later opnieuw.'); // Geef geen specifieke DB fouten terug aan de client
}
?> 