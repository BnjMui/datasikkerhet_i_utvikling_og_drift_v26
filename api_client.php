<?php

/**
 * Enkel HTTP API client som bruker cURL
 * støtter GET, POST, PUT, DELETE med JSON filer
 */

/**
 * Lag en HTTP-forespørsel til en API-endepunkt
 * 
 * @param string $method HTTP-metode (GET, POST, PUT, DELETE...)
 * @param string $url Full URL på API-endepunktet
 * @param array|null $data Forespørselslast (blir JSON-kodet)
 * @param array $headers Tilleggs-HTTP-hoder
 * @param int $timeout Forespørsels timeout i sekunder
 * @return array Responsmatrise med nøkler: ok, status, body, json, error
 */
function api_request(string $method, string $url, ?array $data = null, array $headers = [], int $timeout = 10)
{
    if (!function_exists('curl_init')) {
        return ['ok' => false, 'error' => 'cURL not available'];
    }

    $baseUrl = "http://localhost:8001/api";
    $ch = curl_init();
    $method = strtoupper($method);

    // Sett standard hoder
    $defaultHeaders = ['Accept: application/json'];
    $allHeaders = array_merge($defaultHeaders, $headers);

    // For GET-forespørsler, legg data til som query-parametre i URL-en
    if ($method === 'GET' && $data !== null) {
        $url .= '?' . http_build_query($data);
    } elseif ($data !== null) {
        // For andre metoder, send data som JSON-body
        $payload = json_encode($data);
        $allHeaders[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    // Konfigurer cURL-alternativer
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    // Utfør forespørsel
    $responseBody = curl_exec($ch);
    $curlErr = curl_error($ch);

    // Håndter cURL-feil
    if ($responseBody === false) {
        return [
            'ok' => false,
            'error' => $curlErr ?: 'Unknown cURL error',
            'status' => 0
        ];
    }

    // Prøv å dekode JSON-svar
    $decoded = json_decode($responseBody, true);

    // Returner responsomslag
    return $decoded;
}
