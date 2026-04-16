<?php

namespace DatasikkerhetG7\Api;

require __DIR__ . "/../../vendor/autoload.php";
use DatasikkerhetG7\Logger\DG7Logger;
use DatasikkerhetG7\Repository\Repository;

// api/helpers.php
// Felles hjelpefunksjoner for alle API-endepunkter

header('Content-Type: application/json');
# header('Access-Control-Allow-Origin: http://158.39.188.223');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Helpers
{
    public static function repository(): Repository
    {
        $repository = new Repository();
        return $repository;
    }

    // ---------- SVAR ----------

    public static function send_response(mixed $response, int $code = 200): void
    {
        http_response_code($code);
        die(json_encode($response));
    }

    public static function send_success(mixed $data = null, string $message = "", int $code = 200): void
    {
        $response = ['success' => true];
        if ($message) {
            $response['message'] = $message;
        }
        if ($data) {
            $response['data']    = $data;
        }
        self::send_response($response, $code);
    }

    public static function send_error(string $message = "Bad Request", int $code = 400, string $details = ""): void
    {
        if ($details) {
            error_log("API Error [$code]: $details");
        }
        self::send_response(['success' => false, 'error' => $message], $code);
    }

    // ---------- INPUT ----------

    public static function get_method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function get_request_data(): mixed
    {
        return array_merge(
            empty($_POST) ? [] : $_POST,
            (array) json_decode(file_get_contents('php://input'), true),
            $_GET
        );
    }

    public static function validate_required(mixed $data, mixed $fields): void
    {
        $missing = array_filter($fields, fn ($f) => empty(trim($data[$f] ?? '')));
        if ($missing) {
            $logger = new DG7Logger("data_validation");
            $log = $logger->getLogger();

            $log->warning("Data validation failed", ["expected_data_fields" => $fields, "received_data" => $data]);

            self::send_error('Manglende felter: ' . implode(', ', $missing), 400);
            exit;
        }
    }

    // ---------- AUTENTISERING ----------

    public static function require_auth(): mixed
    {
        if (!isset($_SERVER["HTTP_AUTHENTICATION"])) {
            self::send_error("Unauthorized", 401);
            exit;
        }

        return $authenticated = self::authenticate($_SERVER["HTTP_AUTHENTICATION"]);
    }

    public static function authenticate(string $user_token): mixed
    {
        $repository = new Repository();

        $user_id = $repository->getUserById($user_token);

        if ($user_token == $user_id->user_id) {
            return [
                "authenticated" => true,
                "user_id" => $user_id->user_id,
                "role" => $user_id->role
            ];
        } else {
            $logger = new DG7Logger("authentication");
            $log = $logger->getLogger();

            $log->warning("Failed authentication attempt, using token: ", ["user_token" => $user_token]);

            self::send_error("Unauthorized", 401);
            return null;
        }
    }
}
