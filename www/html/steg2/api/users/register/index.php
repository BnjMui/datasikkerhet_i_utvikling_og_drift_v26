<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/api/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;
use DatasikkerhetG7\Models\CreateLecturerDto;
use DatasikkerhetG7\Models\CreateStudentDto;

$method = Helpers::get_method();
$data = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === 'POST') {

    if ($data["role"] == "student") {
        $valid = Helpers::validate_required($data, ['first_name', 'last_name', 'mail', 'password', "role", "security_questions", 'study_field', 'class_year']);
    }

    if ($data["role"] == "lecturer") {
        $valid = Helpers::validate_required($data, ["first_name", "last_name", "mail", "password", "role", "avatar", "security_questions", "course_code", "course_name", "pin_code"]);
    }

    if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
        Helpers::send_error("Invalid email", 400);
    }

    if (strlen($data['password']) < 8) {
        Helpers::send_error('Password must be atleast 8 characters', 400);
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    switch ($data["role"]) {
        case 'student':
            $user_object = new CreateStudentDto();

            $user_object->first_name = $data["first_name"];
            $user_object->last_name = $data["last_name"];
            $user_object->mail = $data["mail"];
            $user_object->password = $hashedPassword;
            $user_object->role = $data["role"];
            ## TODO
            # $user_object->security_questions = new array();

            $user_object->study_field = $data["study_field"];
            $user_object -> class_year = $data["class_year"];

            $success = $repository->createStudent($user_object);
            break;
        case 'lecturer':
            $user_object = new CreateLecturerDto();
            $hashed_security_answer = password_hash($data["security_answer"], PASSWORD_BCRYPT);

            $user_object->first_name = $data["first_name"];
            $user_object->last_name = $data["last_name"];
            $user_object->mail = $data["mail"];
            $user_object->password = $hashedPassword;
            $user_object->role = $data["role"];

            ## TODO
            # $user_object->security_questions = new array();

            $user_object->avatar = $data["avatar"];
            $user_object->security_question = $data["security_question"];
            $user_object->security_answer = $hashed_security_answer;

            $user_object->course->course_code = $data["course_code"];
            $user_object->course->course_name = $data["course_name"];
            $user_object->course->pin_code = $data["pin_code"];

            $success = $repository->createLecturer($user_object);
            break;
        default:
            $success = false;
            break;
    }
    if ($success) {
        Helpers::send_success(null, "Created", 204);
        exit;
    }

    Helpers::send_error("Internal Server Error", 500);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
