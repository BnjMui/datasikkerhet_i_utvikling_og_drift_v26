<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Api\Helpers;
use DatasikkerhetG7\Models\CreateBaseMessageReply;

$method = Helpers::get_method();
$data = Helpers::get_request_data();
$repository = Helpers::repository();

# if ($method === "GET")
# {
#     // Authentiser
#     // Hvis autentisert returner alle meldinger til kurs
#     // Hvis ikke autentisert sjekk etter pin
#     // Hvis pin returner alle meldinger til kurs
# }

if ($method === "POST")
{
    Helpers::validate_required($data, ["message_id", "text"]);

    $report = new CreateBaseMessageReply();

    $report->message_id = $data["message_id"];
    $report->text = $data["text"];

    $result = $repository->createReport($report);

    if ($result) {
        Helpers::send_success(["success" => true], "Success", 200);
    }
}

Helpers::send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
