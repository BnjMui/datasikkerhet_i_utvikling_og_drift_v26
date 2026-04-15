<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;


$method = Helpers::get_method();
$data = Helpers::get_request_data();

$repository = Helpers::repository();

if ($method === "GET") {
    # Get Courses
    if (!$data) {
        $result = $repository->getCourses();
    }

    if (isset($data["course_id"])) {
        if (!isset($data["pin_code"])) {
            $authenticated = Helpers::require_auth();
        }
        if (isset($data["pin_code"])) {
            $course_pin = $repository->getCoursePin($data["course_id"]);
            if ($data["pin_code"] != $course_pin) {
                Helpers::send_error("Unauthorized", 401);
            }
        }

        $course_data = $repository->getCourseById($data["course_id"]);
        $lecturer_data = $repository->getLecturerDataById($course_data->lecturer_id);

        $result = [
            "course" => $course_data,
            "lecturer" => $lecturer_data
        ];
    }

    if (isset($data["student_id"]) && $data["student_id"] == Helpers::require_auth()["user_id"]) {
        $result = $repository->getStudentCourses($data["student_id"]);
    }

    if (isset($result)) {
        Helpers::send_success($result, "OK");
    }
}



if ($method === "POST") {
    Helpers::validate_required($data, ["student_id", "course_id"]);

    $authenticated = Helpers::require_auth();

    if (!$authenticated["authenticated"] || $authenticated["role"] != "student") {
        Helpers::send_error("Unauthorized", 401);
        exit;
    }

    if ($result = $repository->addCourseToStudent($data["student_id"], $data["course_id"])) {
        Helpers::send_success(["success" => true], "Success", 200);
        exit;
    }
}

Helpers::send_response(['success' => false, 'error' => 'Internal server error'], 500);
