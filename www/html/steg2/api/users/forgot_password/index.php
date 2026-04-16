<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;
use DatasikkerhetG7\Logger\DG7Logger;

$method = Helpers::get_method();
$data = Helpers::get_request_data();
$repository = Helpers::repository();

if ($method === "GET") {
    if (!$data["mail"]) {
        Helpers::send_error("Bad Request", 404);
        exit;
    }

    $security_questions = $repository->getSecurityQuestionsByMail($data["mail"]);

    $logger = new DG7Logger("API");
    $log = $logger->getLogger();

    $log->notice("Security questions requested for user requested", ["used_mail" => $data["mail"]]);

    Helpers::send_success($security_questions, "Success", 200);
    exit;
}

if ($method === 'POST') {
    Helpers::validate_required($data, ["mail", "security_answer", "new_password"]);


    $security_answers = $repository->getSecurityAnswersByMail($data["mail"]);
    if (!password_verify($data["security_answer"], $repository->getSecurityAnswersByMail($data["mail"])->security_answer)) {

        $logger = new DG7Logger("API");
        $log = $logger->getLogger();

        $log->warning("Failed forgot password attempt", ["used_mail" => $data["mail"]]);
        Helpers::send_error("Bad Request", 400);
        exit;
    }




    # if (!password_verify($data["security_answers"], $security_answers["security_answer"])) {
    #     Helpers::send_error("Answer is not correct", 404);
    # }


    $hashed_password = password_hash($data["new_password"], PASSWORD_BCRYPT);
    $user_id = $repository->getUserLoginInfo($data["mail"])->user_id;
    $success = $repository->updatePasswordByUserId($user_id, $hashed_password);

    if ($success) {
        $logger = new DG7Logger("API");
        $log = $logger->getLogger();

        $log->notice("User password changed", ["user_id" => $user_id]);
    }

    Helpers::send_success(["success" => true], "Success", 200);

}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
