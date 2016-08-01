<?php

/**
 *	Every Flight route which we will allow
 *
 *  This file introduces every flight route which we will allow to use. Callback
 *  should be always class method, and class files should always live in
 *  inc/contsollers directory.
 *
 * @package	Coffter
 * @since		1.0.0
 */

require 'inc/controllers/CoffterDefault.php';
require 'inc/controllers/CoffterApiAuth.php';
require 'inc/controllers/CoffterEntries.php';
require 'inc/controllers/CoffterStats.php';

/**
 *  Index
 *
 *  @since	1.0.0
 */
Flight::route( '/', array( 'CoffterDefault', 'index' ) );

/**
 * API VERSION 1 index
 *
 *  @since	1.0.0
 */
Flight::route( '/v1', array( 'CoffterDefault', 'index' ) );

/**
 *  API key request
 *
 *  @since	1.0.0
 */
Flight::route( '/v1/key_request/@email', array( 'CoffterApiAuth', 'key_request' ) );

/**
 *  All coffee consumption statistics
 *
 *  @since	1.0.0
 */
Flight::route( '/v1/coffee', array( 'CoffterStats', 'all' ) );

/**
 *  Coffee consume entry saving
 *
 *  @since	1.0.0
 */
Flight::route( '/v1/coffee/consume/@key', array( 'CoffterEntries', 'register' ) );

/**
 *  Spesific period coffee consumption statistics
 *
 *  @since	1.0.0
 */
Flight::route( '/v1/coffee/drunk/@period', array( 'CoffterStats', 'period' ) );

/**
 *  How much coffee is left
 *
 *  @since	1.0.0
 */
Flight::route( '/v1/coffee/left', array( 'CoffterStats', 'left' ) );
