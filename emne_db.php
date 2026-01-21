<?php
// Emnedata - denne filen kan inkluderes i alle sider som trenger emneinfo

$emner = [
    [
        'kode' => 'INF100',
        'navn' => 'Innføring i programmering',
        'pin' => '1234',
        'foreleser' => [
            'navn' => 'Professor Kari Hansen',
            'bilde' => 'https://via.placeholder.com/100x100? text=KH',
            'email' => 'kari.hansen@universitet.no'
        ]
    ],
    [
        'kode' => 'INF115',
        'navn' => 'Databaser',
        'pin' => '5678',
        'foreleser' => [
            'navn' => 'Professor Ole Berg',
            'bilde' => 'https://via.placeholder.com/100x100?text=OB',
            'email' => 'ole.berg@universitet.no'
        ]
    ],
    [
        'kode' => 'MAT111',
        'navn' => 'Matematikk 1',
        'pin' => '9012',
        'foreleser' => [
            'navn' => 'Professor Lisa Vik',
            'bilde' => 'https://via.placeholder.com/100x100?text=LV',
            'email' => 'lisa. vik@universitet.no'
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
