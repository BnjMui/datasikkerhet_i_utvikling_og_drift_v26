<?php
// api/helpers.php
// Felles hjelpefunksjoner for alle API-endepunkter

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://158.39.188.223');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require_once 'Includes/db.php';
// $database = new Database('localhost', 'datasikkerhet', 'brukernavn', 'passord');
$database = new Database('127.0.0.1', 'datasikkerhet', 'root', 'dev');
$db = $database->getDb();

// ---------- SVAR ----------

function send_response($response, $code = 200) {
    http_response_code($code);
    die(json_encode($response));
}

function send_success($data = null, $message = null, $code = 200) {
    $response = ['success' => true];
    if ($message) $response['message'] = $message;
    if ($data)    $response['data']    = $data;
    send_response($response, $code);
}

function send_error($message, $code = 400, $details = null) {
    if ($details) error_log("API Error [$code]: $details");
    send_response(['success' => false, 'error' => $message], $code);
}

// ---------- INPUT ----------

function get_method() {
    return $_SERVER['REQUEST_METHOD'];
}

function get_request_data() {
    return array_merge(
        empty($_POST) ? [] : $_POST,
        (array) json_decode(file_get_contents('php://input'), true),
        $_GET
    );
}

function validate_required($data, $fields) {
    $missing = array_filter($fields, fn($f) => empty(trim($data[$f] ?? '')));
    if ($missing) send_error('Manglende felter: ' . implode(', ', $missing), 400);
}

// ---------- AUTENTISERING ----------

function require_auth() {
    if (empty($_SESSION['student_id'])) send_error('Innlogging kreves', 401);
    return $_SESSION['student_id'];
}

function start_session($student) {
    $_SESSION['student_id']    = $student['user_id'];
    $_SESSION['student_email'] = $student['mail'];
    $_SESSION['student_name']  = $student['first_name'] . ' ' . $student['last_name'];
}

// ---------- FORMAT ----------

function format_student($row) {
    return [
        'id'          => $row['user_id'],
        'first_name'  => $row['first_name'],
        'last_name'   => $row['last_name'],
        'email'       => $row['mail'],
        'study_field' => $row['study_field'],
        'class_year'  => $row['class_year'],
    ];
}
