<?php

require_once __DIR__ . "/" . "../../helpers.php";

$method = get_method();
$data = get_request_data();

# if ($method === "GET")
# {
#     // Authentiser
#     // Hvis autentisert returner alle meldinger til kurs
#     // Hvis ikke autentisert sjekk etter pin
#     // Hvis pin returner alle meldinger til kurs
# }

if ($method === "POST") {
    validate_required($data, ["pin_code", "course_id", "message_id", "text"]);

    $pin_code = $data["pin_code"];
    $course_pin = repository()->getCoursePin($data["course_id"]);

    if ($pin_code != $course_pin) {
        send_error("Unauthorized", 401);
    }

    $comment = new BaseMessageReplyType();

    $comment->message_id = $data["message_id"];
    $comment->text = $data["text"];

    $result = repository()->createComment($comment);

    if ($result) {
        send_success(null, "Success", 204);
    }
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
