<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Auth\Authenticable;
use App\Socket\Weatherable;
use App\Auth\Settings;
use App\Socket\Constants;

class Weather implements MessageComponentInterface
{
    use Authenticable, Weatherable, Settings;

    /**
     * @var $container
     */
    private $container;

    /**
     * @var $connections
     */
    protected $connections;

    /**
     * Weather constructor.
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container) {
        $this->container   = $container;
        $this->connections = [];
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection) {
        $headers  = $connection->WebSocket->request->getHeaders();
        $ip       = isset($headers['x-forwarded-for']) ? $headers['x-forwarded-for'] : $connection->remoteAddress;

        $auth     = $this->authenticate($connection);
        $isNew    = $auth[0];
        $identity = $auth[1];

        /**
         * Send the initial weather data based
         * on user's IP
         */

        if ( $ip == '127.0.0.1' )
        {
            $ip = '94.231.116.134';
        }

        /**
         * Send the user settings
         */
        if ( $isNew )
        {
            $this->makeSettings($identity);
            $connection->send(sanitize(Constants::SOCKET_SET_SETTINGS, static::$settings));
        }
        else
        {
            $connection->send(sanitize(Constants::SOCKET_SET_SETTINGS, $this->getSettings($identity)));
        }

        $location = $this->getLocation($ip);
        $response = $this->getWeather($identity, $location);
        $connection->send(sanitize(Constants::SOCKET_ACTION_SEARCH, $response));
    }

    /**
     * @param ConnectionInterface $from
     * @param string $message
     */
    public function onMessage(ConnectionInterface $from, $message) {
        $parsed     = json_decode($message);
        $event      = $parsed[0];
        $data       = $parsed[1];
        $identifier = $this->connections[$from->resourceId]->identifier;

        switch ( $event )
        {
            case Constants::SOCKET_ACTION_SEARCH:
            {
                note('info', sprintf("Client with id %s is trying to search for %s.", $from->resourceId, $message));
                $response = $this->getWeather($identifier, $data);
                $from->send(sanitize(Constants::SOCKET_ACTION_SEARCH, $response));
            } break;

            case Constants::SOCKET_SET_SETTINGS:
            {
                note('info', sprintf("Client with id %s is updating settings.", $from->resourceId));
                $response = $this->setSettings($identifier, $data);

                if ( !$response )
                {
                    return;
                }

                $data->updated = true;
                $from->send(sanitize(Constants::SOCKET_SET_SETTINGS, $data));
            } break;

            default:
            {
                note('info', sprintf("Client with id %s has tried to send invalid action %s to socket server.", $from->resourceId, $event));
            } break;
        }
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection) {
        /**
         * Detach the connection
         */

        note('info', sprintf("Client with id %s has disconnected.", $connection->resourceId));
        unset($this->connections[$connection->resourceId]);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Exception $exception
     */
    public function onError(ConnectionInterface $connection, \Exception $exception) {

    }
}