<?php

/**
 * Front to the Coffter application. This file doesn't do anything, but loads
 * depencies which does and presents the magic.
 *
 * @package Coffter
 */

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

require 'inc/routes.php';

/**
 *  Start the Flight, e.g. start the routing framework
 */
Flight::start();
