<?php
namespace DatasikkerhetG7\Frontend;

use CurlHandle;

class ApiClient
{
    private static string $_api_base_url = "http://localhost/steg2/api/";


    # Courses
    public static function get_course(string $course_id, string $pin_code = ""): array | false
    {
        if ($pin_code != "") {
            $response = self::handleRequest(self::curlHandler("GET", "courses?course_id=$course_id&pin_code=$pin_code"));

            if ($response === false) {
                return false;
            }

            $message_data = self::handleRequest(self::curlHandler("GET", "messages?course_id=$course_id&pin_code=$pin_code"));

            $data = ["lecturer" => $response["data"]["lecturer"], "course" => $response["data"]["course"], "messages" => $message_data === false ? false : $message_data["data"]];

            if ($response["success"]) {
                return $data;
            }
            return $response["success"];
        }

        $auth_token = $_SESSION["session_data"]["user_id"];

        $response = self::handleRequest(self::curlHandler("GET", "/courses?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]));

        if ($response === false) {
            return false;
        }

        $message_data = self::handleRequest(self::curlHandler("GET", "/messages?course_id=$course_id", [], ["AUTHENTICATION: $auth_token"]));

        $data = ["lecturer" => $response["data"]["lecturer"], "course" => $response["data"]["course"], "messages" => $message_data === false ? false : $message_data["data"]];

        if ($response["success"]) {
            return $data;
        }
        return $response["success"];

    }

    public static function get_courses(): array | false
    {
        $data = self::handleRequest(self::curlHandler("GET", "/courses"));

        if ($data === false) {
            return false;
        }

        if ($data["success"]) {
            return $data["data"];
        }
        return $data["success"];
    }

    public static function get_student_courses(): array | false
    {
        $auth_token = $_SESSION["session_data"]["user_id"];
        $data = self::handleRequest(self::curlHandler("GET", "/courses?student_id=$auth_token", [], ["AUTHENTICATION: $auth_token"]));

        if ($data === false) {
            return false;
        }

        if ($data["success"] && isset($data["data"])) {
            return $data["data"];
        }
        return $data["success"];
    }

    public static function student_add_course(string $student_id, int $course_id): bool
    {
        $auth_token = $_SESSION["session_data"]["user_id"];
        $response = self::handleRequest(self::curlHandler("POST", "/courses", ["student_id" => $student_id, "course_id" => $course_id], ["AUTHENTICATION: $auth_token"]));

        if ($response === false) {
            return false;
        }

        return $response["success"];
    }

    public static function create_message(int $course_id, string $text): bool
    {
        $auth_token = $_SESSION["session_data"]["user_id"];
        $data = self::handleRequest(self::curlHandler("POST", "/messages", ["course_id" => $course_id, "text" => $text], ["AUTHENTICATION: $auth_token"]));

        if ($data === false) {
            return false;
        }

        return $data["success"];
    }

    public static function create_reply(string $message_id, string $text): bool
    {
        $auth_token = $_SESSION["session_data"]["user_id"];
        $response = self::handleRequest(self::curlHandler("POST", "/messages/replies", ["message_id" => $message_id, "text" => $text], ["AUTHENTICATION: $auth_token"]));

        if ($response === false) {
            return false;
        }

        return $response["success"];
    }

    public static function create_comment(int $course_id, string $text, string $pin_code, string $message_id): bool
    {
        $response = self::handleRequest(self::curlHandler("POST", "/messages/comments", ["course_id" => $course_id, "text" => $text, "pin_code" => $pin_code, "message_id" => $message_id]));

        if ($response === false) {
            return false;
        }

        return $response["success"];
    }

    public static function create_report(string $message_id, string $text): bool
    {
        $response = self::handleRequest(self::curlHandler("POST", "/messages/reports", ["message_id" => $message_id, "text" => $text]));

        if ($response === false) {
            return false;
        }

        return $response["success"];
    }

    # Login
    public static function login($mail, $password): array | false
    {
        $data = self::handleRequest(self::curlHandler("POST", "/users/login", ["mail" => $mail, "password" => $password]));

        if ($data === false) {
            return false;
        }

        if ($data["success"]) {
            return $data["data"];
        }
        return $data["success"];
    }


    public static function register_student(string $first_name, string $last_name, string $mail, string $password, string $study_field, int $class_year): bool
    {
        $role = "student";

        $result = self::handleRequest(self::curlHandler("POST", "/users/register", [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "mail" => $mail,
            "role" => $role,
            "password" => $password,
            "study_field" => $study_field,
            "class_year" => $class_year
        ]));

        if ($result === false) {
            return false;
        }

        return $result["success"];
    }

    public static function register_lecturer(string $first_name, string $last_name, string $mail, string $password, string $avatar, array $security_questions, string $course_code, string $course_name, string $pin_code)
    {
        $role = "lecturer";

        $result = self::handleRequest(self::curlHandler("POST", "/users/register", [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "mail" => $mail,
            "role" => $role,
            "password" => $password,
            "avatar" => $avatar,
            "security_questions" => $security_questions,
            "course_code" => $course_code,
            "course_name" => $course_name,
            "pin_code" => $pin_code
            ]));
        ;

        if ($result === false) {
            return false;
        }

        return $result["success"];
    }

    ## Update this
    public static function change_password( $new_password): bool
    {
        $user_id_token = $_SESSION["session_data"]["user_id"];
        $result = self::handleRequest(self::curlHandler("POST", "/users/update_password", ["new_password" => $new_password], ["AUTHENTICATION: $user_id_token"]));

        if ($result === false) {
            return false;
        }

        return $result["success"];
    }

    # Update this
    public static function get_security_questions($mail): array | false
    {
        $result = self::handleRequest(self::curlHandler("GET", "/users/forgot_password?mail=$mail"));

        if ($result === false) {
            return false;
        }

        return $result["data"];
    }

    # Update this
    public static function forgot_password($mail, $security_answer, $new_password): bool
    {
        # validate_required($data, ["mail", "security_answer", "new_password"]);
        $result = self::handleRequest(self::curlHandler("POST", "/users/forgot_password", ["mail" => $mail, "security_answer" => $security_answer, "new_password" => $new_password]));

        if ($result === false) {
            return false;
        }
        return true;
    }

    private static function handleRequest(CurlHandle $curlHandler): array | false
    {
        $response = curl_exec($curlHandler);

        # TODO:
        # Log Curl error with logger
        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true);
        return $data;
    }

    private static function curlHandler(string $method, string $url, array $data = [], array $headers = []): CurlHandle | false
    {
        $curl = curl_init();
        $method = strtoupper($method);

        if ($method == "POST") {
            $payload = json_encode($data);
            $headers[] = "Content-Type: application/json";
            curl_setopt_array(
                $curl,
                [
                    CURLOPT_POSTFIELDS => $payload
                ]
            );
        }

        // Konfigurer cURL-alternativer
        $curl_opts = [
            CURLOPT_URL => self::$_api_base_url . $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 1000

        ];

        curl_setopt_array($curl, $curl_opts);

        return $curl;
    }
}
