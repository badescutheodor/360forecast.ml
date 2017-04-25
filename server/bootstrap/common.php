<?php

/**
 * Short function for logging
 *
 * @param $type
 * @param $message
 */

function note($type, $message) {
    global $app;
    $app->get('logger')->{$type}($message);
}

/**
 * Short function for config
 *
 * @param $item
 */

function config($item) {
    global $app;
    return $app->get('config')->get($item);
}

/**
 * Helper for getting root folder path
 * @return string
 */
function rootPath() {
    return ROOT_PATH;
}

/**
 * Helper for getting app folder path
 * @return string
 */
function appPath() {
    return ROOT_PATH.'/app';
}

/**
 * Sanitize the data for the socket
 * transport
 */
function sanitize($name, $payload) {
    return json_encode([$name, $payload]);
}
