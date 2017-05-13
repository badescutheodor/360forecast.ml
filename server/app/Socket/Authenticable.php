<?php

namespace App\Socket;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;


trait Authenticable
{
    /**
     * @param $cookie
     * @return bool|string
     */
    protected function getIdentity($cookie) {
        try
        {
            return Crypto::decrypt($cookie, Key::loadFromAsciiSafeString(config('secret')));
        }
        catch(WrongKeyOrModifiedCiphertextException $exception)
        {
            return false;
        }
    }

    /**
     * @return string
     */
    protected function makeIdentity() {
        return Crypto::encrypt(uniqid(), Key::loadFromAsciiSafeString(config('secret')));
    }

    /**
     * Get an IP's city
     */
    public function getLocation($ip = false) {
        if ( !$ip )
        {
            return false;
        }

        try
        {
            $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip));
            return property_exists($location, 'city') ? $location->city : false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}