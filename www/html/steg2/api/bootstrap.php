<?php
require __DIR__ . "/../../../vendor/autoload.php";

header('Content-Type: application/json');
# header('Access-Control-Allow-Origin: http://158.39.188.223');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
