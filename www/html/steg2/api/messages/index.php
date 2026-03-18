<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authorization.php';

$method = get_method();
$data = get_request_data();
$auth = new Authorization();

if ($method === "GET") {
    if (isset($data["pin_code"])) {
        $pin_code = $data["pin_code"];
    }

    if (!isset($pin_code)) {
        $authenticated = $auth->require_auth();
    }

    if (isset($pin_code)) {
        $course_pin = repository()->getCoursePin($data["course_id"]);
        if ($pin_code != $course_pin && !$authenticated["authenticated"]) {
            send_error("Unauthorized", 401);
        }
    }

    $result = repository()->getMessages($data["course_id"]);

    foreach ($result as $message) {
        $message_id = $message->message_id;

        $message_replies = repository()->getReplies($message_id);
        $message_comments = repository()->getComments($message_id);

        $message->replies = $message_replies;
        $message->comments = $message_comments;
    }

    send_success($result, "Success", 200);
}

if ($method === "POST") {
    validate_required($data, ["course_id", "text"]);
    $authenticated = $auth->require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "student") {
        send_error("Unauthorized", 401);
    }

    $student_courses = repository()->getStudentCourses($authenticated["user_id"]);
    $student_has_course = false;
    foreach ($student_courses as $course) {
        if ($course->course_id == $data["course_id"]) {
            $student_has_course = true;
        }
    }
    if (!$student_has_course) {
        send_error("Unauthorized", 401);
    }

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