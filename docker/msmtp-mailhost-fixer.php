<?php
/**
 * Plugin Name:       MSMTP Hostname Fixer
 * Plugin URI:        https://marioyepes.com
 * Description:       Prevents <a href="https://stackoverflow.com/questions/52336131/why-wont-i-send-an-email-thru-wordpress-when-i-can-send-it-thru-a-php-script">errors</a> when sending emails from localhost trough msmtp
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Mario Yepes
 * Author URI:        https://marioyepes.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

add_filter( 'wp_mail_from', function($from){
    return preg_replace( '/(.*)@localhost$/i', '${1}@localhost.devenv', $from );
} );

