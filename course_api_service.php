<?php

include_once 'api_client.php';

function get_course($course_id, $pin_code = null)
{
    if ($pin_code) {
        $response = api_request("GET", "/courses?course_id=$course_id&pin_code=$pin_code");

        $message_data = api_request("GET", "/messages?course_id=$course_id&pin_code=$pin_code");
        $data = ["lecturer" => $response["lecturer"], "course" => $response["course"], "messages" => $message_data];

        return $data;
    }

    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("GET", "/courses?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]);

    $message_data = api_request("GET", "/messages?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]);
    $data = ["lecturer" => $response["lecturer"], "course" => $response["course"], "messages" => $message_data];

    return $data;
}

function get_courses()
{
    $course = "courses";
    return api_request("GET", "/courses");
}

function get_messages($course_code = null, $pin_code = null)
{
    $messages = "";

    return $messages;
}

function create_message($course_id, $text)
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("POST", "/messages", ["course_id" => $course_id, "text" => $text], ["AUTHENTICATION: $auth_token"]);
    return $response;
}

function create_reply($message_id, $text)
{
    $auth_token = $_SESSION["session_data"]["user_id"];
    $response = api_request("POST", "/messages/replies", ["message_id" => $message_id, "text" => $text], ["AUTHENTICATION: $auth_token"]);
    return $response;
}

function create_comment($course_id, $text, $pin_code, $message_id)
{
    $response = api_request("POST", "/messages/comments", ["course_id" => $course_id, "text" => $text, "pin_code" => $pin_code, "message_id" => $message_id]);
    return $response;
}

function create_report($message_id, $text)
{
    $response = api_request("POST", "/messages/reports", ["message_id" => $message_id, "text" => $text]);
    return $response;
}
