<?php

/**
 *	Statistics controller for API
 *
 * @package Coffter
 * @since 1.0.0
 */

require 'inc/models/CoffterDatabase.php';

class CoffterStats {

  private function knatsort(&$karr){
    $kkeyarr = array_keys($karr);
    natsort($kkeyarr);
    $ksortedarr = array();
    foreach($kkeyarr as $kcurrkey){
      $ksortedarr[$kcurrkey] = $karr[$kcurrkey];
    }
    $karr = $ksortedarr;
    return true;
  }

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
	 *  Return week consumption hour by hour.
	 *
	 *  @since   1.1.0
	 *  @version 1.0.0
	 */
  private function week_hour_by_hour( $return = false ) {
    $start_time = date( 'Y-m-d H:00:00', strtotime( '-1 week' ) );
  	$end_time = date( 'Y-m-d H:59:59', strtotime( '-1 week' ) );
    $stop_loop = date( 'Y-m-d H:59:59' );

    $data = array();
  	while( strtotime( $start_time ) <= strtotime( $stop_loop ) ) {
      $count = CoffterDatabase::count_entries( $start_time, $end_time );

      $data[ $start_time ] = $count;

      $start_time = date ( 'Y-m-d H:00:00', strtotime( '+1 hour', strtotime( $start_time ) ) );
      $end_time = date ( 'Y-m-d H:59:59', strtotime( '+1 hour', strtotime( $end_time ) ) );
  	}

    if( $return )
			return $data;

    $return = array(
			'success'        => 'Coffee stats generated',
      'current_time'   => date( 'Y-m-d H:i:s' ),
			'data'           => $data
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
  } // end week_hour_by_hour

  /**
	 *  Return month consumption day by day.
	 *
	 *  @since   1.1.0
	 *  @version 1.0.0
	 */
  private function month_day_by_day( $return = false ) {
    $start_date = date( 'Y-m-d', strtotime( '-1 month' ) );
  	$end_date = date( 'Y-m-d 23:59:59' );

    $data = array();
  	while( strtotime( $start_date ) <= strtotime( $end_date ) ) {
      $count = CoffterDatabase::count_entries( $start_date.' 00:00:00', $start_date.' 23:59:59' );

      $data[ $start_date ] = $count;

      $start_date = date ( 'Y-m-d', strtotime( '+1 day', strtotime( $start_date ) ) );
  	}

    if( $return )
			return $data;

    $return = array(
			'success' => 'Coffee stats generated',
			'data'	  => $data
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
  } // end month_day_by_day

  /**
	 *  Return six month consumption week by week.
	 *
	 *  @since   1.1.0
	 *  @version 1.0.0
	 */
  private function halfyear_week_by_week( $return = false ) {
    $minusdays = date( 'N' )-1; // reset start day to week start
    $start_week = date( 'Y-m-d', strtotime( "-6 month {$minusdays} day" ) );
  	$end_week = date( 'Y-m-d 00:00:00' );

    $data = array();
  	while( strtotime( $start_week ) <= strtotime( $end_week ) ) {
      $new_start_week = date( 'Y-m-d', strtotime( '+1 week', strtotime( $start_week ) ) );

      $count = CoffterDatabase::count_entries( $start_week.' 00:00:00', $new_start_week.' 00:00:00' );

      $data[ date( 'Y W', strtotime( $start_week ) ) ] = $count;

      $start_week = $new_start_week;
  	}

    if( $return )
			return $data;

    $return = array(
			'success' => 'Coffee stats generated',
			'data'	  => $data
		);

    if( isset( $_GET['callback'] ) ):
		  Flight::jsonp( $return, 'callback' );
    else:
      Flight::json( $return );
    endif;
  } // end halfyear_week_by_week

	/**
	 *  Call spesific period function to count it's consumption.
	 *
	 *  @since   1.0.0
	 *  @version 1.0.1
	 *  @param   boolean   $period What period to count
	 */
	public function period( $period, $return = false ) {
		if( $period === 'day' ) {
			$return = self::day( $return );

      if( $return )
        return $return;
    }

		if( $period === 'week' ) {
			$return = self::week( $return );

      if( $return )
        return $return;
    }

		if( $period === 'month' ) {
			$return = self::month( $return );

      if( $return )
        return $return;
    }

		if( $period === 'year' ) {
			$return = self::year( $return );

      if( $return )
        return $return;
    }

    if( !$return ) {
  		Flight::json( array(
  			'error' => 'stats period not spesified'
  		), 400 );
    }
	} // end period

  /**
	 *  Call spesific period function to count it's consumption.
	 *
	 *  @since   1.1.0
	 *  @version 1.0.0
	 *  @param   boolean   $period What period to count
	 */
	public function chart( $period, $return = false ) {
		if( $period === 'week' ) {
			$return = self::week_hour_by_hour( $return );

      if( $return )
        return $return;
    }

		if( $period === 'month' ) {
			$return = self::month_day_by_day( $return );

      if( $return )
        return $return;
    }

		if( $period === 'halfyear' ) {
			$return = self::halfyear_week_by_week( $return );

      if( $return )
        return $return;
    }

    if( !$return ) {
  		Flight::json( array(
  			'error' => 'stats period not spesified'
  		), 400 );
    }
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
