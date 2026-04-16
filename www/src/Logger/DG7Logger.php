<?php

namespace DatasikkerhetG7\Logger;

require __DIR__ . '/../../vendor/autoload.php';

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Handler\GelfHandler;
use Monolog\Level;
use Monolog\Logger;

use Psr\Log\LoggerInterface;

#use Monolog\Handler\GelfHandler;

class DG7Logger
{
    private Logger $logger;



    public function __construct(string $name)
    {
        $this->logger = new Logger($name);

        # Push/Stream handler
        #$this->logger->pushHandler(new StreamHandler('php://stdout', Level::Info));

        $transport = new UdpTransport("158.39.288.228", 12201, UdpTransport::CHUNK_SIZE_LAN);
        $publisher = new Publisher();
        $publisher->addTransport($transport);


        $this->logger->pushHandler(new GelfHandler($publisher, Level::Info));


        # Other settings?
    }

    public function getLogger(): LoggerInterface {
        return $this->logger;
    }
}
