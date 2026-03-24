<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/../src/repository.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../src/models/user_login_dto.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../src/classes/InputValidation.php";

// api/helpers.php
// Felles hjelpefunksjoner for alle API-endepunkter
session_start();

header('Content-Type: application/json');
# header('Access-Control-Allow-Origin: http://158.39.188.223');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function repository(): Repository
{
    $repository = new Repository();
    return $repository;
}

// ---------- SVAR ----------

function send_response($response, $code = 200)
{
    http_response_code($code);
    die(json_encode($response));
    exit;
}

function send_success($data = null, $message = null, $code = 200)
{
    $response = ['success' => true];
    if ($message) {
        $response['message'] = $message;
    }
    if ($data) {
        $response['data']    = $data;
    }
    send_response($response, $code);
}

function send_error($message = "Bad Request", $code = 400, $details = null)
{
    if ($details) {
        error_log("API Error [$code]: $details");
    }
    send_response(['success' => false, 'error' => $message], $code);
}

// ---------- INPUT ----------

function get_method()
{
    return $_SERVER['REQUEST_METHOD'];
}

function get_request_data()
{
    return array_merge(
        empty($_POST) ? [] : $_POST,
        (array) json_decode(file_get_contents('php://input'), true),
        $_GET
    );
}

function validate_required($data, $fields)
{
    $validator = new InputValidation();
    $missing = $validator->validateRequired($data, $fields);
    if ($missing) {
        send_error('Manglende felter: ' . implode(', ', $missing), 400);
        exit;
    }
}

