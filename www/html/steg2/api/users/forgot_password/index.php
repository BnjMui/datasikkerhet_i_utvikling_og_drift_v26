<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/classes/Authentication.php';

$method = get_method();
$data = get_request_data();
$repository = new Repository();
$auth = new Authentication($repository);

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

    $result = $auth->forgotPassword($data["mail"], $data["security_answer"], $data["new_password"]);

    if (!$result['success']) {
        send_error($result['message'], 400);
        exit;
    }

    send_success(null, "Password updated", 204);
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);