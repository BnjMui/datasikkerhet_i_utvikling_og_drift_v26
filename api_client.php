<?php

require_once __DIR__ . "/" . "config.php";

function api_request(string $method, string $url, array $data = [], array $headers = [])
{
    if (!function_exists('curl_init')) {
        return ['ok' => false, 'error' => 'cURL not available'];
    }

    $base_api_url = "http://localhost:8001/api";

    $ch = curl_init();
    $method = strtoupper($method);

    if ($method == "POST") {
        $payload = json_encode($data);
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    // Konfigurer cURL-alternativer
    curl_setopt($ch, CURLOPT_URL, $base_api_url . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);

    // Utfør forespørsel
    $response = curl_exec($ch);
    $curlErr = curl_error($ch);

    // Håndter cURL-feil
    if ($response === false) {
        return [
            'success' => false,
            'error' => $curlErr ?: 'Unknown cURL error',
            'status' => 0
        ];
    }

    $data = json_decode($response, true);
    if ($data) {
        return $data;
    }
}
