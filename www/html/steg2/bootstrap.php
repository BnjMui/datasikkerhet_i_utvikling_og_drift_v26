<?php

use DatasikkerhetG7\Logger\DG7Logger;

require __DIR__ . "/../../vendor/autoload.php";

function globalHandler(Throwable $exception) {
    $logger = new DG7Logger("Logger");
    $log = $logger->getLogger();

    $log->error("Code: " . $exception->getCode() . "Message: " . $exception->getMessage(), [$exception]);
}
