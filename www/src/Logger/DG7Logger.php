<?php

namespace DatasikkerhetG7\Logger;

require __DIR__ . '/../../vendor/autoload.php';

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

        # Push/Stream handler
        $this->logger->pushHandler(new StreamHandler('php://stdout', Level::Info));


        # Other settings?
        
    }

    public function getLogger(): LoggerInterface {
        return $this->logger;
    }
}
