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
        $this->register();
    }

    /**
     * Boot the application
     */
    public function boot() {
        $this->container['server']->boot();
    }

    /**
     * Returns a service from the container
     *
     * @param $property
     * @return bool|mixed
     */
    public function get($property) {
        return isset($this->container[$property]) ? $this->container[$property] : false;
    }

    /**
     * Register the container methods
     */
    private function register() {
        /**
         * Load the environment variables
         */
        $dotenv = new Dotenv\Dotenv('.');
        $dotenv->load();

        /**
         * Set default date time to UTC
         */

        date_default_timezone_set('UTC');

        /**
         * @return \Monolog\Logger
         */
        $this->container['logger'] = function() {
            $logger = new Monolog\Logger('logger');
            $logger->pushHandler(new Monolog\Handler\StreamHandler('./storage/logs/logs.out'));
            $logger->pushHandler(new Monolog\Handler\StreamHandler('php://stdout'));
            return $logger;
        };

        /**
         * @return \Noodlehaus\Config
         */
        $this->container['config'] = function() {
            return new Noodlehaus\Config('./config');
        };

        /**
         * Boot Eloquent
         */

        $capsule = new Illuminate\Database\Capsule\Manager();
        $driver  = getenv('DATABASE_DRIVER') ? getenv('DATABASE_DRIVER') : 'mysql';

        $capsule->addConnection($this->container['config']->get($driver));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        /**
         * @param $container
         * @return Server
         */
        $this->container['server'] = function($container) {
            return new Server($container);
        };
    }
}