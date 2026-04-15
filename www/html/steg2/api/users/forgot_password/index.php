<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;

$method = Helpers::get_method();
$data = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === "GET") {
    if (!$data["mail"]) {
        Helpers::send_error("Bad Request", 404);
        exit;
    }

    $security_questions = $repository->getSecurityQuestionsByMail($data["mail"]);

    Helpers::send_success($security_questions, "Success", 200);
    exit;
}

if ($method === 'POST') {
    Helpers::validate_required($data, ["mail", "security_answers", "new_password"]);


    $security_answers = $repository->getSecurityAnswersByMail($data["mail"]);
    foreach ($data["security_answers"] as $question) {
        foreach ($security_answers as $security_answer) {
            if ($question["question_id"] == $security_answer["question_id"]) {
                if (!password_verify($question["security_answer"], $security_answer["security_answer"])) {
                    Helpers::send_error("Bad Request", 400);
                    exit;
                }
            }
        }
    }



    # if (!password_verify($data["security_answers"], $security_answers["security_answer"])) {
    #     Helpers::send_error("Answer is not correct", 404);
    # }


    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);
    $user_id = $repository->getUserLoginInfo($data["mail"])->user_id;
    $success = $repository->updatePasswordByUserId($user_id, $hashed_password);

    Helpers::send_success(null, "Password updated", 204);
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
