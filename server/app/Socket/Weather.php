<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Weather implements MessageComponentInterface
{
    /**
     * @var $container
     */
    private $container;

    /**
     * Weather constructor.
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection) {
        $this->container['logger']->info(sprintf('A new connection was made from IP: %s', $connection->remoteAddress));
    }

    /**
     * @param ConnectionInterface $from
     * @param string $message
     */
    public function onMessage(ConnectionInterface $from, $message) {

    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection) {

    }

    /**
     * @param ConnectionInterface $connection
     * @param Exception $e
     */
    public function onError(ConnectionInterface $connection, \Exception $e) {

    }
}