<?php

class CoffterDefault {
  public static function index() {
    Flight::view()->set( 'today', CoffterStats::period( 'day', true ) );
    Flight::view()->set( 'week', CoffterStats::period( 'week', true ) );
    Flight::view()->set( 'month', CoffterStats::period( 'month', true ) );
    Flight::view()->set( 'year', CoffterStats::period( 'year', true ) );
    Flight::view()->set( 'charts', array(
      'week'      => json_encode( CoffterStats::chart( 'week', true ) ),
      'month'     => json_encode( CoffterStats::chart( 'month', true ) ),
      'halfyear'  => json_encode( CoffterStats::chart( 'halfyear', true ) )
    ) );

    Flight::render('charts');
  }
}
