<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/api/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;

$method = Helpers::get_method();
$data   = Helpers::get_request_data();

if ($method === 'POST') {
    Helpers::validate_required($data, ["new_password"]);

    $authenticated = Helpers::require_auth();

    if (!$authenticated["authenticated"]) {
        Helpers::send_error("Unauthorized", 401);
        exit;
    }

    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);

    $success = Helpers::repository()->updatePasswordByUserId($authenticated["user_id"], $hashed_password);

    Helpers::send_success(null, "Password updated", 204);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
