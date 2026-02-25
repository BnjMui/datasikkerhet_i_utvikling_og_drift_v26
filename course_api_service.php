<?php

include_once __DIR__ . "/" . 'api_client.php';

function get_course($course_id, $pin_code = null)
{
    if ($pin_code) {
        $response = api_request("GET", "/courses?course_id=$course_id&pin_code=$pin_code");

        $message_data = api_request("GET", "/messages?course_id=$course_id&pin_code=$pin_code");
        $data = ["lecturer" => $response["data"]["lecturer"], "course" => $response["data"]["course"], "messages" => $message_data["data"]];

        if ($response["success"]) {
            return $data;
        }
        return $response["success"];

    }

    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("GET", "/courses?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]);

    $message_data = api_request("GET", "/messages?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]);
    $data = ["lecturer" => $response["data"]["lecturer"], "course" => $response["data"]["course"], "messages" => $message_data];

    if ($response["success"]) {
        return $data;
    }
    return $response["success"];
}

function get_courses()
{
    $course = "courses";
    $data = api_request("GET", "/courses");

    if ($data["success"]) {
        return $data["data"];
    }
    return $data["success"];
}

function get_student_courses()
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $course = "courses";
    $data = api_request("GET", "/courses?student_id=$auth_token", [], ["AUTHENTICATION: $auth_token"]);

    if ($data["success"] && isset($data["data"])) {
        return $data["data"];
    }
    return [];
}

function student_add_course($student_id, $course_id)
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("POST", "/courses", ["student_id" => $student_id, "course_id" => $course_id], ["AUTHENTICATION: $auth_token"]);
    return $response;
}

function create_message($course_id, $text)
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $data = api_request("POST", "/messages", ["course_id" => $course_id, "text" => $text], ["AUTHENTICATION: $auth_token"]);

    return $data["success"];
}

function create_reply($message_id, $text)
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("POST", "/messages/replies", ["message_id" => $message_id, "text" => $text], ["AUTHENTICATION: $auth_token"]);

    return $response["success"];
}

function create_comment($course_id, $text, $pin_code, $message_id)
{
    $response = api_request("POST", "/messages/comments", ["course_id" => $course_id, "text" => $text, "pin_code" => $pin_code, "message_id" => $message_id]);

    return $response["success"];
}

function create_report($message_id, $text)
{
    $response = api_request("POST", "/messages/reports", ["message_id" => $message_id, "text" => $text]);

    return $response["success"];
}
