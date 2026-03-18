<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authentication.php';

$method = get_method();
$data = get_request_data();
$repository = new Repository();
$auth = new Authentication($repository);

if ($method === 'POST') {
    if ($data["role"] == "student") {
        validate_required($data, ['first_name', 'last_name', 'mail', 'password', "role", 'study_field', 'class_year']);
    }
    if ($data["role"] == "lecturer") {
        validate_required($data, ["first_name", "last_name", "mail", "password", "role", "avatar", "security_question", "security_answer", "course_code", "course_name", "pin_code"]);
    }

    $result = $auth->register($data);

    if ($result['success']) {
        send_success(null, "Created", 204);
        exit;
    }

    send_error($result['message'] ?? "Internal Server Error", 500);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);