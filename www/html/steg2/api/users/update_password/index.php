<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authorization.php';

$auth = new Authorization();

if (!$auth->isLoggedIn()) {
    send_error("Unauthorized", 401);
    exit;
}

$method = get_method();
$data   = get_request_data();

if ($method === 'POST') {
    validate_required($data, ["new_password"]);

    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);

    $success = repository()->updatePasswordByUserId($_SESSION['user_id'], $hashed_password);

    send_success(null, "Password updated", 204);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);