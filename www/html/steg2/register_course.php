<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/bootstrap.php";

use DatasikkerhetG7\Frontend\ApiClient;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = isset($_POST['course_id']) ? $_POST["course_id"] : null;
    $response = ApiClient::student_add_course($_SESSION["session_data"]["user_id"], $course_id);

    header("Location: /steg2");
}
