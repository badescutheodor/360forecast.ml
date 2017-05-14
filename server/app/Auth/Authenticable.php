<?php

namespace App\Auth;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use GuzzleHttp\Client;
use App\Socket\Constants;

trait Authenticable
{
    /**
     * @param $connection
     * @return mixed
     */
    protected function authenticate($connection) {
        $headers = $connection->WebSocket->request->getHeaders();
        $ip      = isset($headers['x-forwarded-for']) ? $headers['x-forwarded-for'] : $connection->remoteAddress;
        $isNew   = 0;

        /**
         * Attach the connection
         */

        $this->connections[$connection->resourceId] = $connection;

        /**
         * Log the connection
         */

        note('info', sprintf('A new connection was made from IP: %s', $ip));
        parse_str($connection->WebSocket->request->getQuery(), $parsed);

        /**
         * Set an identity if it does not
         * have one already
         */

        if ( !isset($parsed["identity"]) )
        {
            note('info', sprintf("A new socket connection with resourceId [%s] was created, sending identity.", $connection->resourceId));
            $isNew    = 1;
            $identity = $this->makeIdentity();
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity[1]));
            $connection->identifier = $identity[0];
        }

        /**
         * Try decoding the identity token,
         * else send a new one
         */

        if ( isset($parsed['identity']) && ( $identity = $this->getIdentity($parsed['identity']) ) && !$isNew )
        {
            note('info', sprintf("User with identification [%s] has connected.", $identity[0]));
            $connection->identifier = $identity[0];
        }
        elseif ( isset($parsed['identity']) && !$isNew )
        {
            note('error', sprintf("Could not decrypt identity %s, sending a new one to user.", $parsed["identity"]));
            $identity = $this->makeIdentity();
            $isNew    = 1;
            $connection->send(sanitize(Constants::SOCKET_SET_IDENTIFICATION, $identity[1]));
            $connection->identifier = $identity[0];
        }

        return [$isNew, $identity[0]];
    }

    /**
     * @param $cookie
     * @return array|bool
     */
    protected function getIdentity($cookie) {
        try
        {
            return [Crypto::decrypt($cookie, Key::loadFromAsciiSafeString(config('secret'))), $cookie];
        }
        catch(WrongKeyOrModifiedCiphertextException $exception)
        {
            return false;
        }
    }

    /**
     * @return array
     */
    protected function makeIdentity() {
        $identity = uniqid();
        return [$identity, Crypto::encrypt($identity, Key::loadFromAsciiSafeString(config('secret')))];
    }

    /**
     * @param bool $ip
     * @return bool
     */
    public function getLocation($ip = false) {
        if ( !$ip )
        {
            return false;
        }

        try
        {
            $client   = new Client();
            $response = $client->request('GET', 'http://ip-api.com/json/'.$ip);
            $location = json_decode($response->getBody());
            return property_exists($location, 'city') ? $location->city : false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}