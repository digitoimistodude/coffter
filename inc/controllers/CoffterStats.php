<?php

/**
 *	Statistics controller for API
 * 
 * @package Coffter
 * @since 1.0.0
 */

require 'inc/models/CoffterDatabase.php';

class CoffterStats {

	/**
	 *  Return todays consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $return Should we return count or send json
	 *  @return  int            	 If we should return count, that we will do
	 */
  private function day( $return = false ) {
		$count = CoffterDatabase::count_entries( date( 'Y-m-d 00:00:00' ), date( 'Y-m-d 23:59:59' ) );

		if( $return )
			return $count;

		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'count'		=> $count,
				'period'	=> 'day'
			)
		) );
	} // end day

	/**
	 *  Return this week consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $return Should we return count or send json
	 *  @return  int            	 If we should return count, that we will do
	 */
	private function week( $return = false ) {

		// get first and last day of week
		$date = time();
		$week_start = date( 'Y-m-d', strtotime( '-'.( date( 'N', $date )-1 ).' days', $date ) );
		$week_end = date( 'Y-m-d', strtotime( '-'.date( 'w', $date ).' days', $date ) );

		$count = CoffterDatabase::count_entries( $week_start, $week_end );

		if( $return )
			return $count;

		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'count'		=> $count,
				'period'	=> 'week'
			)
		) );
	} // end week

	/**
	 *  Return this month consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $return Should we return count or send json
	 *  @return  int            	 If we should return count, that we will do
	 */
	private function month( $return = false ) {
		// t = number of days in the given month
		$count = CoffterDatabase::count_entries( date( 'Y-m-01 00:00:00' ), date( 'Y-m-t 23:59:59' ) );

		if( $return )
			return $count;

		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'count'		=> $count,
				'period'	=> 'month'
			)
		) );
	} // end month

	/**
	 *  Return this year consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $return Should we return count or send json
	 *  @return  int            	 If we should return count, that we will do
	 */
	private function year( $return = false ) {
		$count = CoffterDatabase::count_entries( date( 'Y-01-01 00:00:00' ), date( 'Y-12-31 23:59:59' ) );

		if( $return )
			return $count;

		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'count'		=> $count,
				'period'	=> 'year'
			)
		) );
	} // end year

	/**
	 *  Call spesific period function to count it's consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $period What period to count
	 */
	public function period( $period ) {
		if( $period === 'day' )
			self::day();

		if( $period === 'week' )
			self::week();

		if( $period === 'month' )
			self::month();

		if( $period === 'year' )
			self::year();

		Flight::json( array(
			'error' => 'stats period not spesified'
		), 400 );
	} // end period

	/**
	 *  Return all consumption statistics together.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 */
	public function all() {
		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'day'		=> self::day( true ),
				'week'	=> self::week( true ),
				'month'	=> self::month( true ),
				'year'	=> self::year( true )
			)
		) );
	} // end all
} // end CoffterStats
