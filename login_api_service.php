<?php

// Simulerte brukere - studenter og forelesere
require_once __DIR__ . "/" . 'course_api_service.php';
require_once __DIR__ . "/" . "api_client.php";


function get_login($mail, $password)
{
    $data = api_request("POST", "/users/login", ["mail" => $mail, "password" => $password]);

    if ($data["success"]) {
        return $data["data"];
    }
    return $data["success"];
}


function register_student($first_name, $last_name, $mail, $password, $study_field, $class_year)
{
    $role = "student";

    $result = api_request("POST", "/users/register", [
        "first_name" => $first_name,
        "last_name" => $last_name,
        "mail" => $mail,
        "role" => $role,
        "password" => $password,
        "study_field" => $study_field,
        "class_year" => $class_year
    ]);
    if ($result["success"]) {
        return $result["success"];
    }
}

function register_lecturer($first_name, $last_name, $mail, $password, $avatar, $security_question, $security_answer, $course_code, $course_name, $pin_code)
{
    $role = "lecturer";

    $result = api_request("POST", "/users/register", [
        "first_name" => $first_name,
        "last_name" => $last_name,
        "mail" => $mail,
        "role" => $role,
        "password" => $password,
        "avatar" => $avatar,
        "security_question" => $security_question,
        "security_answer" => $security_answer,
        "course_code" => $course_code,
        "course_name" => $course_name,
        "pin_code" => $pin_code
        ]);

    if ($result["success"]) {
        return $result["success"];
    }
}





/**
 * Oppdater passord for bruker
 */
function change_password($new_password)
{
    $user_id_token = $_SESSION["session_data"]["user_id"];
    $result = api_request("POST", "/users/update_password", ["new_password" => $new_password], ["AUTHENTICATION: $user_id_token"]);

    return $result["success"];
}
function get_security_question($mail)
{
    $result = api_request("GET", "/users/forgot_password?mail=$mail");
        return $result["data"];
}
function forgot_password($mail, $security_answer, $new_password)
{
    # validate_required($data, ["mail", "security_answer", "new_password"]);
    $result = api_request("POST", "/users/forgot_password", ["mail" => $mail, "security_answer" => $security_answer, "new_password" => $new_password]);

    return $result;
}
