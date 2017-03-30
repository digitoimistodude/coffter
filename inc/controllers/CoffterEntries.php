<?php

/**
 *	Consumptation register controller for API
 *
 * @package Coffter
 * @since 1.0.0
 */

require 'inc/models/CoffterDatabase.php';

class CoffterEntries {

	/**
	 *  Register coffee consumption entry to database.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   string    $bttnid  Unique bt.tn device id
	 *  @param   string    $eventid Unique bt.tn press id
	 *  @param   string    $type    Press type
	 *  @param   string    $time    Press time
	 *  @param   string    $key     Authorization key
	 */
  public static function register( $key ) {
		$auth_id = CoffterApiAuth::key_check( $key );

    $date = new DateTime( '@'.$_GET['time'] );
    $date->setTimezone( new DateTimeZone( 'Europe/Helsinki' ) );

		$insert = CoffterDatabase::insert_entry( array(
			'e_id'		=> $_GET['eventid'],
			'bttn_id'	=> $_GET['bttnid'],
			'auth_id'	=> $auth_id,
			'time'		=> $date->format( 'Y-m-d H:i:s' ),
			'type'		=> $_GET['type']
		) );

		if( !$insert ) {
			Flight::json( array(
				'error' => 'error while saving entry'
			), 400 );
		}

		Flight::json( array(
			'success' => 'entry saved'
		) );
  } // end register
} // end CoffterEntries
