<?php

include_once 'api_client.php';

/**
 * Finn et emne basert på emnekode
 */
function finnEmne($course_id, $pin_code = null)
{
    $response = api_request("GET", "/courses");

    if (!empty($response['data'])) {
        foreach ($response['data'] as $course) {
            if ($course['course_id'] == $course_id) {
                return $course;
            }
        }
    }

    return null;
}

/**
 * Hent alle emner
 */
function hentAlleEmner()
{
    return api_request("GET", "/courses")['data'];
}

/**
 * Sjekk PIN-kode for et emne
 */
function sjekkEmnePin($course_id, $pin_code)
{
    $response = api_request("GET", "/courses?course_id=" . urlencode($course_id) . "&pin_code=" . urlencode($pin_code));
    return !empty($response['data']);
}

/**
 * Sjekk om en foreleser har tilgang til et emne
 */
function harForeleserTilgang($emneKode, $foreleserEmail)
{
    $emne = finnEmne($emneKode);
    if ($emne && isset($emne['foreleser']['email'])) {
        return strtolower($emne['foreleser']['email']) === strtolower($foreleserEmail);
    }
    return false;
}

/**
 * Hent meldinger for et emne (med svar)
 */
function hentMeldinger($emneKode)
{
    if (!isset($_SESSION['meldinger'])) {
        $_SESSION['meldinger'] = [];
    }

    $meldinger = [];
    foreach ($_SESSION['meldinger'] as $melding) {
        if (strtoupper($melding['emne_kode']) === strtoupper($emneKode)) {
            $meldinger[] = $melding;
        }
    }

    // Sorter etter dato (nyeste først)
    usort($meldinger, function ($a, $b) {
        return strtotime($b['dato']) - strtotime($a['dato']);
    });

    return $meldinger;
}

/**
 * Legg til en ny melding fra student
 */
function leggTilMelding($emneKode, $innhold)
{
    if (!isset($_SESSION['meldinger'])) {
        $_SESSION['meldinger'] = [];
    }

    // Generer en unik ID
    $id = count($_SESSION['meldinger']) + 1;

    $nyMelding = [
        'id' => $id,
        'emne_kode' => $emneKode,
        'innhold' => $innhold,
        'dato' => date('Y-m-d H:i:s'),
        'svar' => null
    ];

    $_SESSION['meldinger'][] = $nyMelding;

    return $id;
}

/**
 * Legg til svar fra foreleser på en melding
 */
function leggTilSvar($emneKode, $meldingId, $svarTekst, $foreleserNavn)
{
    if (!isset($_SESSION['meldinger'])) {
        return false;
    }

    foreach ($_SESSION['meldinger'] as &$melding) {
        if ($melding['id'] == $meldingId && strtoupper($melding['emne_kode']) === strtoupper($emneKode)) {
            $melding['svar'] = [
                'tekst' => $svarTekst,
                'forfatter' => $foreleserNavn,
                'dato' => date('Y-m-d H:i:s')
            ];
            return true;
        }
    }

    return false;
}
