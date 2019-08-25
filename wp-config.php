<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */   

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'redcircl_wp' );

/** MySQL database username */
define( 'DB_USER', 'redcircl_mburbag' );

/** MySQL database password */
define( 'DB_PASSWORD', 'L9J;D6}esNtZ' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'k3&R-8R2B|cO}MzzCmID;Br7<rX/}@p-sR!D5@&|Jb^UbS`28+Ee2ij+yRSpL]_d' );
define( 'SECURE_AUTH_KEY',  'QvNUfD^^}<bin`lsBX6 mTHl3~e`~Qu_H+MoFtC]36n53HHR4JEcRm:V1oJVZ(X7' );
define( 'LOGGED_IN_KEY',    'Z*3W.<,@Dy;R ]W]M^,pcFToq:btU$lE4{ViY}Qj;l1oxU5b;!)MHOubWDSb sM@' );
define( 'NONCE_KEY',        't$=qIz{{uE`jE2tfa@U|2ojy:bK5C`-*AvSM57h5R@Z^n0){}MchV?@6yaB.m+V=' );
define( 'AUTH_SALT',        'vxux!47/row]XdtfOA{_x |9I|^J<u#]zX;gQ8jfc!sDyVn9u|Vh~`@Bfk!gD`}X' );
define( 'SECURE_AUTH_SALT', '%WSGl;7KNzx8B+f9+p)?b*]| eAPn0f%*4,`O+FWSN~!A;2VLewbuT`*jw~NgodB' );
define( 'LOGGED_IN_SALT',   '7)Xmp%h$2;|+LfFRpk2B;d))l|%{sNfw?]Pv,3gQ;h(nPI;:Fuf}eq?!+@ioHapq' );
define( 'NONCE_SALT',       ';$?SPR?bm0wS!8NR^i.-Fs]$6Jbg,(w$Jq:$NGm,oIu[:4v6x)ceiSfK>AB#6c>2' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
