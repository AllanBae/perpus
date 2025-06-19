<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_ti6b_uas' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'n;.![` +73czV0;]%q*nj5FJ2ab6jO/B980*%}lo}?{`vV25iW2kh2LW|}9W,R*x' );
define( 'SECURE_AUTH_KEY',  '{0{sQ`*H!+y8~t|<cT[?ipI /+xvN +o2zo1N<TOSQ{K*a[f<sH_7RRgK;HHN?J1' );
define( 'LOGGED_IN_KEY',    '5yw/4w^MnUb>c@0:fmV*+TqODT--SJ3e>Q?*RY:1#3@SenE5zu)zc&lDVZbN//dW' );
define( 'NONCE_KEY',        ' =4z6K7NOH,#j7aw`Qd3WUV]aODWiF%uAmdBnOMq3tBH<2h617V`Skan_BlX`9a(' );
define( 'AUTH_SALT',        'C789Ye6),zCmib|9@ h2vt8gQ48L:,pw`m0?[Be0,&|koGYc!YmYpLe6~7Ku[^h%' );
define( 'SECURE_AUTH_SALT', 'w[l]hD6_/yPj:W d<&-_CsTPLII*&}f7XGuO>T*[}7NdZfrD9(Gxyp_%!9QhJV%I' );
define( 'LOGGED_IN_SALT',   '#9ieu%)5,u5d.]w6olovS?Lj2amznK}x-0Q>iVJm#gMuX?|KHF|Ne-qDh2+/%2m6' );
define( 'NONCE_SALT',       '(U-F?6_S)z}.k68d-4f.n<Z{WRu;Spk7&Ik:+WtgNS/tqoAD!`i71h72n[;vWeID' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
