<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "./steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;

$method = Helpers::get_method();
$data   = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === 'POST') {
    Helpers::validate_required($data, ["mail", "password", "new_password"]);

    $authenticated = Helpers::require_auth();

    if (!$authenticated["authenticated"]) {
        Helpers::send_error("Unauthorized", 401);
        exit;
    }

    $user_data = $repository->getUserLoginInfo($data["mail"]);

if (!$user_data || !password_verify($data["password"], $user_data->password)) {
    Helpers::send_error("User with provided mail or password combination not found", 404);
    exit;
    }

    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);

    $success = $repository->updatePasswordByUserId($authenticated["user_id"], $hashed_password);

    Helpers::send_success(["success" => true], "Success", 200);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
