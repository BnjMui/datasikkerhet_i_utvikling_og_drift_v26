<?php

require_once __DIR__ . "/" . '../../helpers.php';

$method = get_method();
$data   = get_request_data();
$repository = new Repository();

if ($method === 'POST') {
    validate_required($data, ["new_password"]);

    $authenticated = require_auth();

    if (!$authenticated["authenticated"]) {
        send_error("Unauthorized", 401);
        exit;
    }

    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);

    $success = $repository->updatePasswordByUserId($authenticated["user_id"], $hashed_password);

    send_success(null, "Password updated", 204);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
