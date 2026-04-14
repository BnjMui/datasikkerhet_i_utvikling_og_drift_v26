<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/api/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;

$method = Helpers::get_method();
$data   = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === 'POST') {
    Helpers::validate_required($data, ['mail', 'password']);

    $user_data = $repository->getUserLoginInfo($data["mail"]);

    if (!$user_data) {
        Helpers::send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    if (!password_verify($data["password"], $user_data["password"])) {
        Helpers::send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    $result = $repository->getUserById($user_data->user_id);

    Helpers::send_success($result, "Success", 200);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
