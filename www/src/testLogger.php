<?php
require __DIR__ . "/logger/logger.php";
use DatasikkerhetG7;
use DatasikkerhetG7\DG7Logger;
use Psr\Log\LoggerInterface;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new DG7Logger("testLogger");
$log = $logger->getLogger();
$logger->startLogger();

$log->pushHandler(new StreamHandler('logtest.log'), Level::Info);

try {
throw new Exception("en feil har skjedd");
} catch (Exception $e) {
    $log->error($e->getMessage());
}
