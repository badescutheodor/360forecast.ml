<?php

namespace App\Socket;

class Constants
{
    /**
     * Socket events
     */

    const SOCKET_SET_IDENTIFICATION = 'set:identification';
    const SOCKET_ACTION_SEARCH      = 'action:search';
    const SOCKET_SET_SETTINGS       = 'set:settings';

    /**
     * Settings
     */

    const SETTING_SAVE_LOCATION     = 'save:location';
    const SETTING_FORECAST_COUNT    = 'forecast:days:count';

    /**
     * Misc
     */

    const WEAHER_CACHE_NAMESPACE    = 'weather:cache';
}