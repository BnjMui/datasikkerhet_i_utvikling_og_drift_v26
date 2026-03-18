<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authentication.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authorization.php';

$method = get_method();
$data = get_request_data();
$repository = new Repository();
$auth = new Authentication($repository);
$authz = new Authorization();

if (!$authz->isLoggedIn()) {
    send_error("Unauthorized", 401);
    exit;
}

if ($method === 'POST') {
    validate_required($data, ["new_password"]);

    $result = $auth->changePassword($data["new_password"]);

    send_success(null, "Password updated", 204);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);