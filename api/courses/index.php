<?php

// api/subjects.php
// GET /api/subjects.php — hent alle emner

require_once __DIR__ . '/../helpers.php';

$method = get_method();
$data = get_request_data();

if ($method === "GET") {
    if ($data["id"]) {
        $course_data = repository()->getCourseById($data["id"]);
        $lecturer_data = repository()->getLecturerDataById($course_data->lecturer_id);

        $result = [
            "course" => $course_data,
            "lecturer" => $lecturer_data
        ];
    }

    if ($data["student_id"]) {
        $result = repository()->getStudentCourses($data["student_id"]);
    }

    if (!$data) {
        $result = repository()->getCourses();
    }

    send_success($result, "OK");
    exit;
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
