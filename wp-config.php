<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// Use these settings on the local server
if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {
  include( dirname( __FILE__ ) . '/wp-config-local.php' );
  
// Otherwise use the below settings (on live server)
} else {

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'resacad_wrdp1');

/** MySQL database username */
define('DB_USER', 'resacad_wrdp1');

/** MySQL database password */
define('DB_PASSWORD', '36I7jfxnDXqo');

/** MySQL hostname */
define('DB_HOST', 'localhost');
  
// Overwrites the database to save keep editing the DB
define('WP_HOME','http://resoluteacademy.com');
define('WP_SITEURL','http://resoluteacademy.com');

// Turn Debug off on live server
define('WP_DEBUG', false);
  
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'f7sXx[2wbB@LBwlxS{A56y>+36I7jfxnDXqoXz iq\\Ms}?(;_a#ypoR5\'3w<G5>FL~1X');
define('SECURE_AUTH_KEY', '-\"r642fed3E1d>}MdGFlh@tR!QPU%/!S_6;CjO0}^>6T36I7jfxnDXqo+ <?)3 U#*X;');
define('LOGGED_IN_KEY', 'Q^l\"6-pL36I7jfxnDXqo\zkw>OG<U$%u0:9H:Jl)+d$~jIBTt&CPL[Dp<KY4zL3y');
define('NONCE_KEY', 'asdasdjkasdjk36I7jfxnDXqojklasdjklasdkl&^%^&*(&*(jklasdjklasdjkl&*)))');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');