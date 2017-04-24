<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Socket\Weather;

class Server
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var
     */
    private $server;

    /**
     * Server constructor.
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * The socket server boot method
     */
    public function boot() {
        $port = $this->container['config']->get('port');
        $this->container['logger']->info(sprintf('Starting the socket server on port %d.', $port));
        $this->server = IoServer::factory(new HttpServer(new WsServer(new Weather($this->container))), $port);
        $this->server->run();
    }
}