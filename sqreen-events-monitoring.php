<?php
/**
 * Plugin Name: Sqreen Events Monitoring
 * Description: Integrate WP events with Sqreen
 * Version:     0.1
 * Author:      Francesco Carlucci
 * Author URI:  https://frenxi.com
 */

// prevent direct access
defined( 'ABSPATH' ) || exit;

/**
 * Check if Sqreen SDK is existing and active
 *
 * @return bool
 */
function is_sqreen_available() {

  return function_exists('sqreen\auth_track') && function_exists('sqreen\signup_track') && function_exists('sqreen\identify') && function_exists('sqreen\track');

}

add_action( 'init', 'sqreen_identify' );
function sqreen_identify() {

  if( ! is_sqreen_available() ) return;

  $current_user = wp_get_current_user();

  if( $current_user->ID !== 0 ) sqreen\identify( [ 'username' => $current_user->user_login ] );

}

add_action( 'wp_login', 'sqreen_track_login', 10, 2 );
function sqreen_track_login( $user_login, $user ) {

  if( ! is_sqreen_available() ) return;

  sqreen\auth_track( true, [ 'username' => $user_login ] );

}

add_action( 'wp_login_failed', 'sqreen_track_login_fail', 10 );
function sqreen_track_login_fail( $username ) {

  if( ! is_sqreen_available() ) return;

  sqreen\auth_track( false, [ 'username' => $username ] );

}

add_action( 'user_register', 'sqreen_track_user_signup', 10 );
function sqreen_track_user_signup( $user_id ) {

  if( ! is_sqreen_available() ) return;

  $user = get_user_by('id', $user_id);

  if( $user ) sqreen\signup_track( [ 'username' => $user->user_login ] );

}
