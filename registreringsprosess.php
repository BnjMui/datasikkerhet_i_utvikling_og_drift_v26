<?php
session_start();

require_once 'bruker_db.php';
require_once 'api_client.php';

$user_type = $_POST['user_type'] ?? '';
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$studieretning = trim($_POST['studieretning'] ?? '');
$studiekull = trim($_POST['studiekull'] ?? '');
$emne = trim($_POST['emne'] ?? '');
$security_question = trim($_POST['security_question'] ?? '');
$security_answer = trim($_POST['security_answer'] ?? '');

// Validering
$valideringsfeil = [];

if (empty($user_type) || !in_array($user_type, ['student', 'lecturer'])) {
    $valideringsfeil[] = 'Du må velge en brukertype.';
}

if (empty($firstname) || strlen($firstname) < 2) {
    $valideringsfeil[] = 'Fornavn må være minst 2 tegn.';
}

if (empty($lastname) || strlen($lastname) < 2) {
    $valideringsfeil[] = 'Etternavn må være minst 2 tegn.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $valideringsfeil[] = 'Gyldig e-postadresse er påkrevd.';
}

// Password validering - påkrevd for students, optional for lecturers
if ($user_type === 'student') {
    if (empty($password) || strlen($password) < 6) {
        $valideringsfeil[] = 'Passord må være minst 6 tegn.';
    }

    if ($password !== $password_confirm) {
        $valideringsfeil[] = 'Passordene samsvarer ikke.';
    }
} else if ($user_type === 'lecturer') {
    // Lecturers get a default password if none provided
    if (empty($password)) {
        $password = bin2hex(random_bytes(8));
    } else if (strlen($password) < 6) {
        $valideringsfeil[] = 'Passord må være minst 6 tegn.';
    } else if ($password !== $password_confirm) {
        $valideringsfeil[] = 'Passordene samsvarer ikke.';
    }
}

// Validering av studentspesifikke felt
if ($user_type === 'student') {
    if (empty($studieretning) || strlen($studieretning) < 2) {
        $valideringsfeil[] = 'Studieretning må være minst 2 tegn.';
    }
    
    if (empty($studiekull) || !is_numeric($studiekull) || $studiekull < 2000 || $studiekull > 2100) {
        $valideringsfeil[] = 'Studiekull må være et gyldig år mellom 2000 og 2100.';
    }
}

// Validering av forelesersspesifikke felt
if ($user_type === 'lecturer') {
    if (empty($emne)) {
        $valideringsfeil[] = 'Du må velge et emne du underviser i.';
    }

    if (empty($security_question) || strlen($security_question) < 5) {
        $valideringsfeil[] = 'Sikkerhetsspørsmål må være minst 5 tegn.';
    }

    if (empty($security_answer) || strlen($security_answer) < 2) {
        $valideringsfeil[] = 'Sikkerhetssvar må være minst 2 tegn.';
    }
}

// Hvis validering feilet, gå tilbake til register.php med feilmeldinger
if (!empty($valideringsfeil)) {
    header('Location: register.php?error=1&msg=' . urlencode(implode('<br>', $valideringsfeil)));
    exit;
}

// Hash passord
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Håndter bildeoppload for lecturers
$picture = null;
if ($user_type === 'lecturer' && isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";

    // Opprett uploads-mappen hvis den ikke finnes
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_type = mime_content_type($_FILES['picture']['tmp_name']);
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Validering av bildetypen
    if (!in_array($file_type, $allowed_types)) {
        header('Location: register.php?error=1&msg=' . urlencode('Kun JPG, PNG og GIF er tillatt.'));
        exit;
    }

    // Validering av filstørrelse (5MB max)
    if ($_FILES['picture']['size'] > 5 * 1024 * 1024) {
        header('Location: register.php?error=1&msg=' . urlencode('Bildefilen kan ikke være større enn 5MB.'));
        exit;
    }

    // Generer unikt filnavn
    $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
        $picture = $target_file;
    } else {
        header('Location: register.php?error=1&msg=' . urlencode('Feil ved opplasting av bilde. Prøv igjen.'));
        exit;
    }
}

// Legg til bruker i bruker_db.php array
$securityAnswerHash = null;
if ($user_type === 'lecturer' && !empty($security_answer)) {
    $securityAnswerHash = password_hash($security_answer, PASSWORD_DEFAULT);
}

$newUserId = registrerBruker($user_type, $firstname, $lastname, $email, $hashedPassword, $picture, $studieretning, $studiekull, $emne, $security_question, $securityAnswerHash);

if ($newUserId) {
    // Optional: Send registration data to API
    $apiEnabled = false; // Set to true and update URL to enable API calls
    $apiUrl = 'https://api.example.com/register'; // Replace with your API endpoint
    
    if ($apiEnabled) {
        $apiPayload = [
            'user_type' => $user_type,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'studieretning' => $studieretning,
            'studiekull' => $studiekull,
            'emne' => $emne
        ];
        
        $apiResponse = api_post($apiUrl, $apiPayload);
        
        if (!$apiResponse['ok']) {
            error_log('API registration failed: ' . $apiResponse['error'] ?? $apiResponse['body']);
        }
    }
    
    // Omdirigerer til login med suksessmelding
    header('Location: login.php?success=1');
    exit;
} else {
    // Hvis registrering feilet (f.eks. e-post allerede eksisterer)
    header('Location: register.php?error=1&msg=' . urlencode('Registrering feilet. E-posten kan allerede være registrert.'));
    exit;
}
?>
