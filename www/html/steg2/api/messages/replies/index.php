<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/../src/classes/Authorization.php';

$method = get_method();
$data = get_request_data();
$auth = new Authorization();

if ($method === "GET") {
}

if ($method === "POST") {
    validate_required($data, ["message_id", "text"]);

    $authenticated = $auth->require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "lecturer") {
        send_error("Unauthorized", 401);
    }

    $reply = new BaseMessageReplyType();

    $reply->message_id = $data["message_id"];
    $reply->text = $data["text"];

    $result = repository()->createReply($reply);

    if ($result) {
        send_success(null, "Success", 204);
    }
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);