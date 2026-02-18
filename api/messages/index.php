<?php

require_once __DIR__ . "/" . "../helpers.php";

$method = get_method();
$data = get_request_data();

if ($method === "GET") {
    $pin_code = $data["pin_code"];
    if (!$pin_code) {
        $authenticated = require_auth();
    }

    if ($pin_code) {
        $course_pin = repository()->getCoursePin($data["course_id"]);
        if ($pin_code != $course_pin) {
            send_error("Unauthorized", 401);
        }
        $result = repository()->getMessages($data["course_id"]);
        send_success($result, "Success", 200);
    }

    if (!$authenticated["authenticated"]) {
        send_error("Unauthorized", 401);
    }

    $result = repository()->getMessages($data["course_id"]);

    send_success($result, "Success", 200);
    exit;
}

if ($method === "POST") {
    validate_required($data, ["user_id", "course_id", "text"]);
    // Autentiser som student
    // Hvis ikke autentisert retuner 401
    $authenticated = require_auth();

    if (!$authenticated["authenticated"]) {
        send_error("Unauthorized", 401);
    }
    // Hvis autentisert lagre melding i database
    $message = new CreateMessageDto();

    $message->student_id = $authenticated["user_id"];
    $message->course_id = $data["course_id"];
    $message->text = $data["text"];

    $result = repository()->createMessage($message);

    if ($result) {
        send_success(null, "Success", 204);
    }
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
