<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Socket\Authenticable;
use App\Models\Search;
use App\Socket\Constants;

class Weather implements MessageComponentInterface
{
    use Authenticable;

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
        $this->connections = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection) {
        note('info', sprintf('A new connection was made from IP: %s', $connection->remoteAddress));
        parse_str($connection->WebSocket->request->getQuery(), $parsed);

        /**
         * Attach the connection
         */

        $this->connections->attach($connection);

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
    }

    /**
     * @param ConnectionInterface $from
     * @param string $message
     */
    public function onMessage(ConnectionInterface $from, $message) {
        print_r($message);
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection) {
        /**
         * Detach the connection
         */

        note('info', sprintf("Client with id %s has disconnected.", $connection->resourceId));
        $this->connections->detach($connection);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Exception $exception
     */
    public function onError(ConnectionInterface $connection, \Exception $exception) {

    }
}