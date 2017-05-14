/**
 * Application constants
 * @type {string}
 */
export const SOCKET_SERVER = 'wss://360forecast.ml/socket';


/**
 * Socket constants
 * @type {string}
 */
export const SOCKET_SET_IDENTIFICATION = 'set:identification';
export const SOCKET_ACTION_SEARCH      = 'action:search';
export const SOCKET_SET_SETTINGS       = 'set:settings';


/**
 * Client events
 * @type {string}
 */
export const EVENT_SHOW_OVERLAY    = 'show-overlay';
export const EVENT_HIDE_OVERLAY    = 'hide-overlay';
export const EVENT_SHOW_SEARCH     = 'show-search';
export const EVENT_HIDE_SEARCH     = 'hide-search';
export const EVENT_SHOW_SETTINGS   = 'show-settings';
export const EVENT_HIDE_SETTINGS   = 'hide-settings';
export const EVENT_DATA_LOADED     = 'data-loaded';

/**
 * Client settings
 * @type {string}
 */
export const SETTING_SAVE_LOCATION  = 'save:location';
export const SETTING_FORECAST_COUNT = 'forecast:days:count';