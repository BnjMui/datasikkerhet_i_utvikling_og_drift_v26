<?php

require_once __DIR__ . "/" . "../../helpers.php";

$method = get_method();
$data = get_request_data();

if ($method === "GET")
{
    // Authentiser
    // Hvis autentisert returner alle meldinger til kurs
    // Hvis ikke autentisert sjekk etter pin
    // Hvis pin returner alle meldinger til kurs
}

if ($method === "POST")
{
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    // Hvis autentisert lagre melding i database
}
