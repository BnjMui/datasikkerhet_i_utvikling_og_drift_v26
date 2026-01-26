<?php

//include "db.php";

// // Reference: https://www.geeksforgeeks.org/php/building-a-rest-api-with-php-and-mysql/
//
// header("Content-Type: application/json");
//
// $method = $_SERVER['REQUEST_METHOD'];
// $input = json_decode(file_get_contents('php://input'), true); // input fra POST og PUT requests, ikke GET requests...
//
// switch ($method) {
//     case 'GET':
//         if (isset($_GET['id'])) {
//             $id = $_GET['id'];
//             print_r(json_encode(array("id" => $id, "method" => $method)));
//         } else {
//             echo json_encode("Ingen id gitt");
//         }
//         break;
//     default:
//         echo json_encode(["message" => "Invalid request method"]);
//         break;
// }

// function getTestData($dataNumber)
// {
//     echo "funksjonen ble kalt med $dataNumber som argument";
// }

function login_user($username, $password) {
}

function create_user($username, $password, $type, $user_data) {
}

function create_course($course_name, $course_pin) {
}

function get_courses($user_id) {
    // Return all courses if no user_id is provided
}

function get_course($course_id) {
}

function create_message ($course_id, $text_content) {
}

function create_response($message_id, $user_id, $text_content) {
}

function create_comment($message_id, $text_content) {
}

function create_report($message_id, $text_content) {
}

function get_messages($course_id) {
}

function get_message($message_id) {
}
