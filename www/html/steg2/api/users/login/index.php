<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/../src/classes/Authentication.php';

$method = get_method();
$data   = get_request_data();
$repository = new Repository();
$auth = new Authentication($repository);

if ($method === 'POST') {
    validate_required($data, ['mail', 'password']);
    
    $result = $auth->login($data["mail"], $data["password"]);
    
    if (!$result['success']) {
        send_error($result['message'], 401);
        exit;
    }

    send_success(null, "Success", 200);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);