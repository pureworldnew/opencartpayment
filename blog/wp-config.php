<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gempackedtest_blog');

/** MySQL database username */
define('DB_USER', 'gempacktestblo');

/** MySQL database password */
define('DB_PASSWORD', '1u_tp0M1');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'HK^eQ;~D7D3|^0RA}3&f<ek2I+CzAw$<X?CL 1</{~VjKifjEP[,Q.Oh^@%Z{zn7');
define('SECURE_AUTH_KEY',  'm`!.USm3iHX(=CwODun@`t7AKd#c?.]Y*FPxBbyrr]8++V[-r]O+fG-QquuOJ]!4');
define('LOGGED_IN_KEY',    '9W;Dt7+3s+h-^L$;p566h-7~0aRpSSe2#sS!z!{cUZnq,6_ttiw_4pZ$=b+z5wPV');
define('NONCE_KEY',        'x4uA-=|S)dN0Tfa_|#)zL/289u!+$py+$5/@FJA3Xm6uRv_0FQyI*gi 9HS~C,u]');
define('AUTH_SALT',        'YNO@slP9QT7.%_U<-Q%u)[[FYZrv8CG?Aea*Ad|9LQ%UaMiV `1kKv:OE8y;r_|!');
define('SECURE_AUTH_SALT', 'e:p Sq;6Q@Nt02u5|&~i~yFZ{~_o{SG+d!DB-]Jr?!,Ktf2OwQP^9_PJa:ns&i9e');
define('LOGGED_IN_SALT',   ' z rs 5iju}.vDRO?<@gz4_7hNp>!K`rSd$_2<E4P|3m-!&:J@p>Cbk_5XXZlEYx');
define('NONCE_SALT',       'gFT]oU&4 |Pm[{5X#%FK`HM~D:}1F+iHT^STLg]P+$._g$[+Z!ZL&UMqIa:ud~F?');

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
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


