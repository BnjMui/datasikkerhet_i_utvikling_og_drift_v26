<?php

require_once __DIR__ . "/" . "../../course_api_service.php";
session_start();

$course_code = $_SESSION["prev_course_code"];
$course_id = $_POST["course_id"];
$text = $_POST["message"];
$pin_code = isset($_POST["pin_code"]) ? $_POST["pin_code"] : null;
$message_id = isset($_POST["message_id"]) ? $_POST["message_id"] : null;

if (!$_SESSION["session_data"]) {
    if (!$_POST["report"]) {
        create_comment($course_id, $text, $pin_code, $message_id);
    }
    if ($_POST["report"]) {
            create_report($message_id, $text);
    }
}
if ($_SESSION["session_data"]["role"] == "student") {
    create_message($course_id, $text);
}
if ($_SESSION["session_data"]["role"] == "lecturer") {
    echo print_r([$message_id, $text]);
    create_reply($message_id, $text);
}

header("Location: /course?course_code=$course_code&course_id=$course_id");
