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
  public static function register( $bttnid, $eventid, $type, $time, $key ) {
		$auth_id = CoffterApiAuth::key_check( $key );

		$insert = CoffterDatabase::insert_entry( array(
			'e_id'		=> $eventid,
			'bttn_id'	=> $bttnid,
			'auth_id'	=> $auth_id,
			'time'		=> date( 'Y-m-d H:i:s', $time ),
			'type'		=> $type
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
