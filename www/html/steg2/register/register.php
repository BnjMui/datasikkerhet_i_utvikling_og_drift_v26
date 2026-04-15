<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/bootstrap.php";

use DatasikkerhetG7\Frontend\ApiClient;

$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$mail = $_POST["mail"];
$role = $_POST["role"];
$password = $_POST["password"];

if ($role == "student") {
    ApiClient::register_student(
        $first_name,
        $last_name,
        $mail,
        $password,
        $_POST["study_field"],
        $_POST["class_year"]
    );
}
if ($role == "lecturer") {
    #echo print_r($_FILES["avatar"]);
    $avatar_directory = "profile_avatars/";
    $target_file = $avatar_directory . strtolower($first_name . $last_name) . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if ($_FILES["avatar"]["size"] > 10000000) {
        $uploadOk = 0;
        echo "file too big";
    }

    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
        $uploadOk = 0;
        echo "wrong file type";
    }

    if ($uploadOk != 0) {

        print_r($target_file);
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
        ApiClient::register_lecturer(
            $first_name,
            $last_name,
            $mail,
            $password,
            $target_file,
            $_POST["security_questions"],
            $_POST["course_code"],
            $_POST["course_name"],
            $_POST["pin_code"]
        );
    }
}
header("Location: /steg2/login");
exit;
