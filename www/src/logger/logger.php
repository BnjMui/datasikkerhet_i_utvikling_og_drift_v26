<?php

namespace DatasikkerhetG7;

require __DIR__ . '/../../vendor/autoload.php';

use Error;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Psr\Log\LoggerInterface;

#use Monolog\Handler\GelfHandler;

class DG7Logger
{
    private Logger $logger;



    public function __construct(string $name)
    {
        $this->logger = new Logger($name);

        $this->logger->pushHandler(new StreamHandler('php://stdout', Level::Info));
    }

    public function getLogger(): LoggerInterface {
        return $this->logger;
    }

    public function startLogger()
    {

        $this->logger->info("Starting logger " . $this->logger->getName());
    }
}
