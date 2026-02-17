<?php
// Emnedata - denne filen kan inkluderes i alle sider som trenger emneinfo

$emner = [
    [
        'kode' => 'INF100',
        'navn' => 'Innføring i programmering',
        'pin' => '1234',
        'foreleser' => [
            'navn' => 'Tom Heinert',
            'bilde' => 'https://via.placeholder.com/100x100?text=TH',
            'email' => 'tom.heinert@hiof.no'
        ]
    ],
    [
        'kode' => 'INF115',
        'navn' => 'Databaser',
        'pin' => '5678',
        'foreleser' => [
            'navn' => 'Ole Berg',
            'bilde' => 'https://via.placeholder.com/100x100?text=OB',
            'email' => 'ole.berg@hiof.no'
        ]
    ],
    [
        'kode' => 'MAT111',
        'navn' => 'Matematikk 1',
        'pin' => '9012',
        'foreleser' => [
            'navn' => 'Lisa Vik',
            'bilde' => 'https://via.placeholder.com/100x100?text=LV',
            'email' => 'lisa.vik@hiof.no'
        ]
    ],
];

/**
 * Finn et emne basert på emnekode
 */
function finnEmne($kode)
{
    global $emner;
    foreach ($emner as $emne) {
        if (strtoupper($emne['kode']) === strtoupper($kode)) {
            return $emne;
        }
    }
    return null;
}

/**
 * Hent alle emner
 */
function hentAlleEmner()
{
    global $emner;
    return $emner;
}

/**
 * Sjekk PIN-kode for et emne
 */
function sjekkEmnePin($kode, $pin)
{
    $emne = finnEmne($kode);
    if ($emne && $emne['pin'] === $pin) {
        return true;
    }
    return false;
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
