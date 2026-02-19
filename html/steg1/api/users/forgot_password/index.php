<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/api/helpers.php';

$method = get_method();
$data   = get_request_data();
$repository = new Repository();

if ($method === "GET") {
    if (!$data["mail"]) {
        send_error("Bad Request", 404);
        exit;
    }

    $security_question = $repository->getSecurityQuestionByMail($data["mail"]);

    send_success($security_question, "Success", 200);
    exit;
}

if ($method === 'POST') {
    validate_required($data, ["mail", "security_answer", "new_password"]);
    

    $security_answer = $repository->getSecurityAnswerByMail($data["mail"]);

    if (!password_verify($data["security_answer"], $security_answer["security_answer"])) {
        send_error("Answer is not correct", 404);
    }

    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);

    $success = $repository->updatePasswordByUserId($security_answer["user_id"], $hashed_password);

    send_success(null, "Password updated", 204);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
