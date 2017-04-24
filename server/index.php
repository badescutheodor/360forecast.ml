<?php

/**
 * Vendor autoload
 */
require('./vendor/autoload.php');

/**
 * Bootstrap the application
 *
 */
require('./bootstrap/index.php');

/**
 * Boot the application
 */
$app = new Application();
$app->boot();


