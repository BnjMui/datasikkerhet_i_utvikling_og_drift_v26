<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/api/helpers.php';

$method = get_method();
$data = get_request_data();

if ($method === "GET") {
    # Get Courses
    if (!$data) {
        $result = repository()->getCourses();
    }

    if (isset($data["course_id"])) {
        if (!isset($data["pin_code"])) {
            $authenticated = require_auth();
        }
        if (isset($data["pin_code"])) {
            $course_pin = repository()->getCoursePin($data["course_id"]);
            if ($data["pin_code"] != $course_pin) {
                send_error("Unauthorized", 401);
            }
        }

        $course_data = repository()->getCourseById($data["course_id"]);
        $lecturer_data = repository()->getLecturerDataById($course_data->lecturer_id);

        $result = [
            "course" => $course_data,
            "lecturer" => $lecturer_data
        ];
    }

    if (isset($data["student_id"]) && $data["student_id"] == require_auth()["user_id"]) {
        $result = repository()->getStudentCourses($data["student_id"]);
    }

    if (isset($result)) {
        send_success($result, "OK");
    }
}



if ($method === "POST") {
    validate_required($data, ["student_id", "course_id"]);

    $authenticated = require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "student") {
        send_error("Unauthorized", 401);
        exit;
    }

    if ($result = repository()->addCourseToStudent($data["student_id"], $data["course_id"])) {
        send_success(null, "Course added to student", 204);
        exit;
    }
}

send_response(['success' => false, 'error' => 'Internal server error'], 500);
