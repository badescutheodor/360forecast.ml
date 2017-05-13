<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Socket\Authenticable;
use App\Socket\Weatherable;
use App\Socket\Constants;

class Weather implements MessageComponentInterface
{
    use Authenticable, Weatherable;

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
        $ip = isset($connection->WebSocket->request->getHeaders()['x-forwarded-for']) ? $connection->WebSocket->request->getHeaders()['x-forwarded-for'] : $connection->remoteAddress;


        note('info', sprintf('A new connection was made from IP: %s', $ip));
        parse_str($connection->WebSocket->request->getQuery(), $parsed);

        /**
         * Set an identity if it does not
         * have one already
         */

        if ( !$parsed["identity"] )
        {
            note('info', sprintf("A new socket connection with resourceId [%s] was created, sending identity."), $connection->resourceId);
            $identity = $this->makeIdentity();
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity));
            $connection->identifier = $identity;
            return;
        }

        /**
         * Try decoding the identity token,
         * else send a new one
         */

        if ( $identity = $this->getIdentity($parsed['identity']) )
        {
            note('info', sprintf("User with identification [%s] has connected.", $identity));
            $connection->identifier = $identity;
        }
        else
        {
            note('error', sprintf("Could not decrypt identity %s, sending a new one to user.", $parsed["identity"]));
            $identity = $this->makeIdentity();
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity));
        }

        /**
         * Send the initial weather data based
         * on user's IP
         */

        if ( $ip == '127.0.0.1' )
        {
            $ip = '94.231.116.134';
        }

        $location = $this->getLocation($ip);
        $response = $this->getWeather($identity, $location, 5);
        $connection->send(sanitize(Constants::SOCKET_ACTION_SEARCH, $response));

        /**
         * Attach the connection
         */

        $this->connections[$connection->resourceId] = $connection;
    }

    /**
     * @param ConnectionInterface $from
     * @param string $message
     */
    public function onMessage(ConnectionInterface $from, $message) {
        $parsed = json_decode($message);
        $event  = $parsed[0];
        $data   = $parsed[1];

        switch ( $event )
        {
            case Constants::SOCKET_ACTION_SEARCH:
            {
                note('info', sprintf("Client with id %s is trying to search for %s.", $from->resourceId, $message));
                $response = $this->getWeather($this->connections[$from->resourceId]->identifier, $data, 5);
                $from->send(sanitize(Constants::SOCKET_ACTION_SEARCH, $response));
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