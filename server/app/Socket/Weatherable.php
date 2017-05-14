<?php

namespace App\Socket;

use App\Models\Search;
use App\Socket\Cache;
use App\Socket\Constants;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

trait Weatherable
{
    /**
     * @param $city
     * @return array
     */
    public function getWeather($identity, $city)
    {
        /**
         * Cache
         */

        $cache  = new Cache();
        $cache->setNamespace(Constants::WEAHER_CACHE_NAMESPACE);

        /**
         * OpenWeather
         */

        $lang   = 'en';
        $units  = 'metric';
        $owm    = new OpenWeatherMap(config('weather')['api'], null, $cache, 120);
        $status = true;

        /**
         * Settings
         */

        $settings = $this->getSettings($identity, [
            Constants::SETTING_FORECAST_COUNT,
            Constants::SETTING_SAVE_LOCATION
        ]);

        $days         = $settings[Constants::SETTING_FORECAST_COUNT];
        $saveLocation = $settings[Constants::SETTING_SAVE_LOCATION];


        /**
         * Get the data and return it
         */

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

        echo $saveLocation;

        if ( $saveLocation )
        {
            $search             = new Search;
            $search->text       = $city;
            $search->identifier = $identity;
            $search->save();
        }

        return compact('status', 'errors', 'response');
    }
}