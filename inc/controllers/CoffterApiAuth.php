<?php

/**
 *	Authentication controller for API
 *
 * @package Coffter
 * @since 1.0.0
 */

require_once 'inc/models/CoffterDatabase.php';

class CoffterApiAuth {

	/**
	 *  Respond to API auhorization key request.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   mixed			$email	Expected email for user, but can be everything
	 *                          		since it's really only a free parameter
	 */
  public function key_request( $email ) {
		if( getenv( 'ALLOW_AUTH_REQUEST' ) === 'false' ) {
			Flight::json( array(
				'error' => 'API authorization key requests not allowed'
			), 403 );
		}

    if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			Flight::json( array(
				'error' => 'invalid email address'
			), 400 );
		}

		if( !empty( CoffterDatabase::get_authorization_by( 'email', $email ) ) ) {
			Flight::json( array(
				'error' => 'email address has API key already'
			), 400 );
		}

		$auth = CoffterDatabase::create_authorization( $email );
		Flight::json( array(
			'success' => 'API key granted',
			'details'	=> array(
				'email'	=> $email,
				'key'		=> $auth['key']
			)
		) );
  } // end key_request

	/**
	 *  Check API key.
	 *
	 *  This should be called everytime first things first when auhorizationis needed.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   string    $key API key
	 *  @return  int         		Auhorization id if key is valid, otherwise 401 json
	 */
	public function key_check( $key ) {
		$auth = CoffterDatabase::get_authorization_by( 'auth_key', $key );

		if( empty( $auth ) ) {
			Flight::json( array(
				'error' => 'invalid API key'
			), 401 );
		}

		return $auth['id'];
	} // end key_check
} // end CoffterApiAuth
