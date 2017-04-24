<?php

use App\Socket\Server;

class Application
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Application constructor.
     */
    public function __construct() {
        $this->container = new Pimple\Container();
    }

    /**
     * Boot the application
     */
    public function boot() {
        $this->register();
        $this->container['server']->boot();
    }

    /**
     * Register the container methods
     */
    private function register() {
        $dotenv = new Dotenv\Dotenv('.');
        $dotenv->load();

        $this->container['logger'] = function() {
            $logger = new Monolog\Logger('logger');
            $logger->pushHandler(new Monolog\Handler\StreamHandler('./storage/logs/logs.out'));
            $logger->pushHandler(new Monolog\Handler\StreamHandler('php://stdout'));
            return $logger;
        };

        $this->container['config'] = function() {
            return new Noodlehaus\Config('./config');
        };

        $this->container['server'] = function($c) {
            return new Server($c);
        };
    }
}