<?php

// Simulerte brukere - studenter og forelesere
require_once __DIR__ . "/" . 'course_api_service.php';
require_once __DIR__ . "/" . "api_client.php";


function get_login($mail, $password)
{
    $data = api_request("POST", "/users/login", ["mail" => $mail, "password" => $password]);

    return $data;
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
    return $result;
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
    return $result;
}





/**
 * Oppdater passord for bruker
 */
function oppdaterPassord($userId, $nyttPassord)
{
    global $brukere;

    $hashedPassword = password_hash($nyttPassord, PASSWORD_DEFAULT);

    // Oppdater i memory array
    foreach ($brukere as &$bruker) {
        if ($bruker['id'] === $userId) {
            $bruker['passord'] = $hashedPassword;
            break;
        }
    }

    // Lagre til JSON
    lagreBrukereJson();

    return true;
}
