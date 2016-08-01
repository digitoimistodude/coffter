<?php

/**
 *	Global database model
 *
 *  Description here.
 *
 * @package Coffter
 * @since 1.0.0
 */

class CoffterDatabase {
	private static $initialized = false;
	private static $database = '';

	/**
	 *  Check if class is already initialized, and establish databse connection if
	 *  not. Should be called when ever database is needed.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 */
	function initialize() {
		if( self::$initialized )
    	return;

		self::$database = new medoo([
	    'database_type'	=> 'mysql',
	    'database_name'	=> getenv( 'DB_NAME' ),
	    'server'				=> getenv( 'DB_HOST' ),
	    'username'			=> getenv( 'DB_USER' ),
	    'password'			=> getenv( 'DB_PASS' ),
	    'charset'				=> 'utf8'
		]);
	} // end initialize

	/**
	 *  Get authorization record by a given field.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   string			$field	The field to retrieve the user with
	 *  @param   string			$value	A value for $field
	 *  @return  mixed							Authorization information array on success,
	 *                              false on failure
	 */
	public function get_authorization_by( $field = null, $value = null ) {
		if( is_null( $field ) || is_null( $value ) )
			return false;

		self::initialize();

		return self::$database->get( 'authorization', '*', [
			$field => $value
		] );
	} // end get_authorization_by

	/**
	 *  Create authorization record with a email.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   string			$email	Email for the record
	 *  @return  mixed           		Array containing authorization id and key on
	 *                              success, false on failure
	 */
	public function create_authorization( $email = null ) {
		if( is_null( $email ) )
			return false;

		self::initialize();

		$key = rtrim( base64_encode( md5( microtime().rand().getenv( 'KEY_SALT' ) ) ), '=' );
		$auth_id = self::$database->insert( 'authorization', [
			'auth_key'		=> $key,
			'email'				=> $email,
			'granted_at'	=> date( 'Y-m-d H:i:s' )
		] );

		return array(
			'id'	=> $auth_id,
			'key'	=> $key
		);
	} // end create_authorization

	public function insert_entry( $event = array() ) {
		if( empty( $event ) )
			return false;

		self::initialize();

		$return = self::$database->insert( 'entries', [
			'event_id'		=> $event['e_id'],
			'bttn_id'			=> $event['bttn_id'],
			'auth_id'			=> $event['auth_id'],
			'event_type'	=> $event['type'],
			'event_time'	=> $event['time'],
			'created_at'	=> date( 'Y-m-d H:i:s' )
		] );

		if( is_numeric( $return ) )
			return true;

		return false;
	} // end insert_entry

	public function count_entries( $start_time = null, $end_time = null ) {
		if( is_null( $start_time ) || is_null( $end_time ) )
			return false;

		self::initialize();

		$count = self::$database->count( 'entries', [
			'event_time[<>]' => [ $start_time, $end_time ]
		] );

		return $count;
	} // end count_entries
} // end CoffterDatabase
