<?php

// api/login.php
// POST /api/login.php

require_once __DIR__ . "/" . '../../helpers.php';

$method = get_method();
$data   = get_request_data();
$repository = new Repository();

if ($method === 'POST') {
    validate_required($data, ['mail', 'password']);

    $user_data = $repository->getUserLoginInfo($data["mail"]);

    if (!$user_data) {
        send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    if (password_verify($data["password"], $user_data->password) != $user_data->password) {
        send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    send_success($user_data->user_id, "Success", 200);
}

send_response(['success' => false, 'error' => 'Metode ikke tillatt'], 405);
