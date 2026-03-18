<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/api/helpers.php';

$method = get_method();
$data = get_request_data();

# if ($method === "GET")
# {
#     // Authentiser
#     // Hvis autentisert returner alle meldinger til kurs
#     // Hvis ikke autentisert sjekk etter pin
#     // Hvis pin returner alle meldinger til kurs
# }

if ($method === "POST")
{
    validate_required($data, ["message_id", "text"]);

    $report = new BaseMessageReplyType();

    $report->message_id = $data["message_id"];
    $report->text = $data["text"];

    $result = repository()->createReport($report);

    if ($result) {
        send_success(null, "Success", 204);
    }
}

send_response(['success' => false, 'error' => 'Method Not Allowed'], 405);
