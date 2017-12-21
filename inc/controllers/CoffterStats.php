<?php

/**
 *	Statistics controller for API
 *
 * @package Coffter
 * @since 1.0.0
 */

require_once 'inc/models/CoffterDatabase.php';

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
    $latest_entry = CoffterDatabase::get_latest_entry( date( 'Y-m-d 00:00:00' ), date( 'Y-m-d 23:59:59' ) );

		if( $return )
			return $count;

    $return = array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
        'latest_entry'  => $latest_entry,
				'count'         => $count,
				'period'        => 'day'
			)
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
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
		$week_start = date( 'Y-m-d 00:00:00', strtotime( '-'.( date( 'N', $date )-1 ).' days', $date ) );
    $week_end = date( 'Y-m-d 23:59:59', strtotime( '+'.( date( 'w', $date )+1 ).' days', $date ) );

		$count = CoffterDatabase::count_entries( $week_start, $week_end );
    $latest_entry = CoffterDatabase::get_latest_entry( $week_start, $week_end );

		if( $return )
			return $count;

		$return = array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
        'latest_entry'  => $latest_entry,
				'count'         => $count,
				'period'        => 'week'
			)
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
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
    $latest_entry = CoffterDatabase::get_latest_entry( date( 'Y-m-01 00:00:00' ), date( 'Y-m-t 23:59:59' ) );

		if( $return )
			return $count;

    $return = array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
        'latest_entry'  => $latest_entry,
				'count'         => $count,
				'period'        => 'month'
			)
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
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
    $latest_entry = CoffterDatabase::get_latest_entry( date( 'Y-01-01 00:00:00' ), date( 'Y-12-31 23:59:59' ) );

		if( $return )
			return $count;

    $return = array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
        'latest_entry'  => $latest_entry,
				'count'         => $count,
				'period'        => 'year'
			)
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
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
	 *  Return how many coffee cups are left after last fill.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.0
	 *  @param   boolean   $return Should we return count or send json
	 *  @return  int            	 If we should return count, that we will do
	 */
	public function left( $return = false ) {
		$last_fill = CoffterDatabase::get_latest_entry( 'pressed-long' );
		$count = CoffterDatabase::count_entries( $last_fill['event_time'], date( 'Y-m-d H:i:s' ) );

		$count = getenv( 'CUPS_FROM_FILL' ) - $count;

		if( $return )
			return $count;

		Flight::json( array(
			'success' => 'Coffee stats generated',
			'details'	=> array(
				'count'		=> $count,
			)
		) );
	} // end left

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
				'year'	=> self::year( true ),
				'left'	=> self::left( true )
			)
		) );
	} // end all
} // end CoffterStats
