<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;
use DatasikkerhetG7\Models\CreateBaseMessageReply;

$method = Helpers::get_method();
$data = Helpers::get_request_data();
$repository = Helpers::repository();

 if ($method === "GET")
 {
 }

if ($method === "POST") {
    Helpers::validate_required($data, ["message_id", "text"]);
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    // Hvis autentisert lagre melding i database
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    $authenticated = Helpers::require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "lecturer") {
        Helpers::send_error("Unauthorized", 401);
    }
    // Hvis autentisert lagre melding i database
    $reply = new CreateBaseMessageReply();

    $reply->message_id = $data["message_id"];
    $reply->text = $data["text"];

    $result = $repository->createReply($reply);

    if ($result) {
        Helpers::send_success(null, "Success", 204);
    }
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
