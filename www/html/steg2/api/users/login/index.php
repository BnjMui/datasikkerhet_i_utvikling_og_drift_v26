<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;
use DatasikkerhetG7\Logger\DG7Logger;

$method = Helpers::get_method();
$data   = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === 'POST') {

    $logger = new DG7Logger("API_Login");
    $log = $logger->getLogger();

    Helpers::validate_required($data, ['mail', 'password']);

    $user_data = $repository->getUserLoginInfo($data["mail"]);

    if (!$user_data) {

        $log->warning("Invalid login attempt", ["used_mail" => $data["mail"]]);

        Helpers::send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    if (!password_verify($data["password"], $user_data->password)) {

        $log->warning("Invalid login attempt", ["used_mail" => $data["mail"]]);

        Helpers::send_error("User with provided mail or password combination not found", 404);
        exit;
    }

    $result = $repository->getUserById($user_data->user_id);


    $log->info("User logged in", ["user_id" => $result->user_id]);
    Helpers::send_success($result, "Success", 200);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
