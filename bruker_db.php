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
