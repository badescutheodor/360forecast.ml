<?php

/**
 * Define constants
 */

define('ROOT_PATH', __DIR__);

/**
 * Vendor autoload
 */

require('./vendor/autoload.php');

/**
 * Bootstrap the application
 */

require('./bootstrap/index.php');

/**
 * Register the DI container
 */

$app = new Application();

/**
 * Load the common methods
 * in current context
 */

require(ROOT_PATH.'/bootstrap/common.php');

/**
 * Boot the application
 */

$app->boot();


