<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/api/helpers.php';

$method = get_method();
$data = get_request_data();

 if ($method === "GET")
 {
 }

if ($method === "POST") {
    validate_required($data, ["message_id", "text"]);
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    // Hvis autentisert lagre melding i database
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    $authenticated = require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "lecturer") {
        send_error("Unauthorized", 401);
    }
    // Hvis autentisert lagre melding i database
    $reply = new BaseMessageReplyType();

    $reply->message_id = $data["message_id"];
    $reply->text = $data["text"];

    $result = repository()->createReply($reply);

    if ($result) {
        send_success(null, "Success", 204);
    }
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
