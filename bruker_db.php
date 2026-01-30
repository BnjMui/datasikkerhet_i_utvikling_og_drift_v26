<?php
// Simulerte brukere - studenter og forelesere
require_once 'emne_db.php';

$brukere = [
    // Studenter
    [
        'id' => 1,
        'brukernavn' => 'student1',
        'passord' => 'student123',
        'navn' => 'Erik Moræ',
        'email' => 'erik.@student.no',
        'rolle' => 'student'
    ],
    [
        'id' => 2,
        'brukernavn' => 'student2',
        'passord' => 'student123',
        'navn' => 'Jonas Evensen',
        'email' => 'jonas.evensen@student.no',
        'rolle' => 'student'
    ],
    // Forelesere (må matche med emne_db.php)
    [
        'id' => 3,
        'brukernavn' => 'tom',
        'passord' => 'foreleser123',
        'navn' => 'Professor Tom Heinert',
        'email' => 'tom.heinert@hiof.no',
        'rolle' => 'foreleser'
    ],
    [
        'id' => 4,
        'brukernavn' => 'ole',
        'passord' => 'foreleser123',
        'navn' => 'Professor Ole Berg',
        'email' => 'ole.berg@hiof.no',
        'rolle' => 'foreleser'
    ],
    [
        'id' => 5,
        'brukernavn' => 'lisa',
        'passord' => 'foreleser123',
        'navn' => 'Professor Lisa Vik',
        'email' => 'lisa.vik@hiof.no',
        'rolle' => 'foreleser'
    ],
];

/**
 * Finn bruker basert på brukernavn og passord
 */
function finnBruker($brukernavn, $passord)
{
    global $brukere;
    foreach ($brukere as $bruker) {
        if ($bruker['brukernavn'] === $brukernavn && $bruker['passord'] === $passord) {
            return $bruker;
        }
    }
    return null;
}

/**
 * Finn bruker basert på email (for å koble foreleser til emner)
 */
function finnBrukerMedEmail($email)
{
    global $brukere;
    foreach ($brukere as $bruker) {
        if ($bruker['email'] === $email) {
            return $bruker;
        }
    }
    return null;
}

/**
 * Registrer ny bruker (student eller lecturer)
 * Lagrer data i en JSON-fil siden det ikke er en riktig database
 */
function registrerBruker($user_type, $firstname, $lastname, $email, $hashedPassword, $picture = null)
{
    global $brukere;

    // Sjekk om e-posten allerede finnes
    foreach ($brukere as $bruker) {
        if ($bruker['email'] === $email) {
            return false; // E-posten er allerede registrert
        }
    }

    // Generer nytt bruker-ID
    $newId = max(array_map(function($b) { return $b['id']; }, $brukere)) + 1;

    // Generer brukernavn basert på fornavn + etternavn
    $brukernavn = strtolower($firstname . '.' . $lastname);

    // Opprett ny bruker
    $ny_bruker = [
        'id' => $newId,
        'brukernavn' => $brukernavn,
        'passord' => $hashedPassword, // Lagrer hashet passord
        'navn' => $firstname . ' ' . $lastname,
        'email' => $email,
        'rolle' => ($user_type === 'lecturer') ? 'foreleser' : 'student',
        'bilde' => $picture,
        'registrert_dato' => date('Y-m-d H:i:s')
    ];

    // Legg til i brukere array
    $GLOBALS['brukere'][] = $ny_bruker;

    // Lagre til JSON-fil for persistens
    lagreBrukereJson();

    return $newId;
}

/**
 * Finne bruker med email og password (for registrerte brukere)
 */
function finnBrukerMedEmailOgPassord($email, $passord)
{
    global $brukere;
    foreach ($brukere as $bruker) {
        if ($bruker['email'] === $email && password_verify($passord, $bruker['passord'])) {
            return $bruker;
        }
    }
    return null;
}

/**
 * Lagre brukere til JSON-fil (for persistens av nye registreringer)
 */
function lagreBrukereJson()
{
    global $brukere;
    $jsonFile = 'data/brukere.json';

    // Opprett data-mappen hvis den ikke finnes
    if (!is_dir('data')) {
        mkdir('data', 0755, true);
    }

    file_put_contents($jsonFile, json_encode($brukere, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/**
 * Last inn registrerte brukere fra JSON-fil
 */
function lastBrukereJson()
{
    $jsonFile = 'data/brukere.json';
    if (file_exists($jsonFile)) {
        $json = file_get_contents($jsonFile);
        $registrerteBrukere = json_decode($json, true);
        if (is_array($registrerteBrukere)) {
            return $registrerteBrukere;
        }
    }
    return [];
}

/** Hent emner som en foreleser underviser i*/
function hentForeleserEmner($foreleserEmail)
{
    $alleEmner = hentAlleEmner();
    $foreleserEmner = [];

    foreach ($alleEmner as $emne) {
        if ($emne['foreleser']['email'] === $foreleserEmail) {
            $foreleserEmner[] = $emne;
        }
    }

    return $foreleserEmner;
}
