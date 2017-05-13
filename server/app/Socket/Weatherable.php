<?php

namespace App\Socket;

use App\Models\Search;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

trait Weatherable
{
    /**
     * @param $city
     * @return array
     */
    public function getWeather($identity, $city, $days = 5)
    {
        $lang   = 'en';
        $units  = 'metric';
        $owm    = new OpenWeatherMap(config('weather')['api']);
        $status = true;

        try
        {
            $response = json_decode($owm->getRawDailyForecastData($city, $units, $lang, '', 'json', $days));
        }
        catch(OWMException $e)
        {
            note('error', 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
            $status = false;
            $errors = [];
        }
        catch(\Exception $e)
        {
            note('error', 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
            $status = false;
            $errors = [];
        }

        $search             = new Search;
        $search->text       = $city;
        $search->identifier = $identity;
        $search->save();

        return compact('status', 'errors', 'response');
    }
}