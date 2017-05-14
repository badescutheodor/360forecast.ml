<?php

namespace App\Auth;

use App\Models\Setting;
use App\Socket\Constants;

trait Settings
{
    /**
     * @var array
     */
    static $settings = [
        Constants::SETTING_SAVE_LOCATION  => true,
        Constants::SETTING_FORECAST_COUNT => 5
    ];

    /**
     * @param $identifier
     */
    public function makeSettings($identifier)
    {
        if ( !$identifier )
        {
            note('error', 'Missing identifier while creating initial settings.');
            return;
        }

        foreach ( static::$settings as $key => $value )
        {
            $setting             = new Setting;
            $setting->identifier = $identifier;
            $setting->key        = $key;
            $setting->value      = $value;
            $setting->save();
        }
    }


    /**
     * @param $identifier
     * @param $settings
     * @return bool
     */
    public function setSettings($identifier, $settings)
    {
        $status = true;

        foreach ( $settings as $key => $value )
        {
            if ( !isset(static::$settings[$key]) )
            {
                $status = false;
                note('error', sprintf('User with identifier %s tried to update a setting that doesn\'t exist: %s, value: %s.', $identifier, $key, json_encode($value)));
                continue;
            }

            $updated = Setting::where('identifier', $identifier)->where('key', $key)->update([
                "value" => $value
            ]);

            if ( !$updated )
            {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * @param $identifier
     * @param array $keys
     * @return array|bool
     */
    public function getSettings($identifier, $keys = [])
    {
        $returned = [];
        $settings = Setting::where('identifier', $identifier);
        $hasKeys  = count($keys) > 0;


        if ( $hasKeys )
        {
            $settings = $settings->whereIn('key', $keys);
        }

        $settings = $settings->get();


        if ( !$hasKeys )
        {
            foreach ( $settings as $setting )
            {
                $returned[$setting->key] = $setting->value;
            }
        }
        else
        {
            foreach ( $keys as $key )
            {
                foreach ( $settings as $setting )
                {
                    if ( $setting->key == $key )
                    {
                        $returned[$key] = $setting->value;
                        continue 2;
                    }
                    else
                    {
                        $returned[$key] = static::$settings[$key];
                    }
                }
            }
        }

        return $returned ? $returned : false;
    }
}