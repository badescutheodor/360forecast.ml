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
