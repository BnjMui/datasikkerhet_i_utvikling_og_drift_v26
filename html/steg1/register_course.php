<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/../course_api_service.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = isset($_POST['course_id']) ? $_POST["course_id"] : null;
    $response = student_add_course($_SESSION["session_data"]["user_id"], $course_id);

        header("Location: /steg1");
}
