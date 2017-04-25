<?php

namespace App\Socket;

use Pimple\Container;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use App\Models\Search;
use App\Socket\Constants;

class Weather implements MessageComponentInterface
{
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
            $identity = Crypto::encrypt(uniqid(), Key::loadFromAsciiSafeString(config('secret')));
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity));
            $connection->identifier = $identity;
            return;
        }

        /**
         * Try decoding the identity token,
         * else send a new one
         */

        try
        {
            $identity = Crypto::decrypt($parsed["identity"], Key::loadFromAsciiSafeString(config('secret')));
        }
        catch(WrongKeyOrModifiedCiphertextException $exception)
        {
            note('error', sprintf("Could not decrypt identity %s, sending a new one to user.", $parsed["identity"]));
            $identity = Crypto::encrypt(uniqid(), Key::loadFromAsciiSafeString(config('secret')));
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity));
        }

        note('info', sprintf("User with identification [%s] has connected.", $identity));
        $connection->identifier = $identity;
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