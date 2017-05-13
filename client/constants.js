/**
 * Application constants
 * @type {string}
 */
export const SOCKET_SERVER = 'ws://localhost:8080';


/**
 * Socket constants
 * @type {string}
 */
export const SOCKET_SET_IDENTIFICATION = 'set:identification';
export const SOCKET_ACTION_SEARCH      = 'action:search';


/**
 * Client events
 * @type {string}
 */
export const EVENT_SHOW_OVERLAY = 'show-overlay';
export const EVENT_HIDE_OVERLAY = 'hide-overlay';
export const EVENT_SHOW_SEARCH  = 'show-search';
export const EVENT_HIDE_SEARCH  = 'hide-search';
export const EVENT_DATA_LOADED  = 'data-loaded';