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
define('DB_NAME', 'masco_data');

/** MySQL database username */
define('DB_USER', 'masco');

/** MySQL database password */
define('DB_PASSWORD', '1');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '+nB2<eO0$^+6NtY{g*njo(F~_7XjU{+fuzCTSwzu1W,1hx{8`nNXWdPBxU#.K^))');
define('SECURE_AUTH_KEY',  '?Phh$|7}z<9oQNOPe>`Bh<D xN_[7qKxGkr|a0hmmR=:, t<~5.c{VL_(7S KS&Q');
define('LOGGED_IN_KEY',    '<|]#(/k4RV$`/~TMv|ZEqg*:nc8!>Ifys^QYV7Bmvzkdgds$Lzq{Q4YbPq>rF:g4');
define('NONCE_KEY',        '=t#20j8i)GpoYPm!fv:=Oy`Iq5<|B;=tiE^|j3[f2sxxhR{{*iZ-$Jjb.+/*F14D');
define('AUTH_SALT',        'Gs>! 9C}J~`H!~g~AM;XetY?!FuqS^USu*L(B<WC !]O?SSf22([IkjZ8OGrFsW}');
define('SECURE_AUTH_SALT', 'kbs1AJ)2{Sf8?EQRh$m3..whZOi7SZe!%<{JtQU:G6JK7H`+v,z:)8P*_B=.>,aJ');
define('LOGGED_IN_SALT',   '+x4(G^W+KfD4M8xZ.9=T?n~,Y6=i3__5O`5__I88moLGS%::LbYa ;!OG]#zJE2C');
define('NONCE_SALT',       '(7-BIz(S.)] -1J;F`HMn2`;e=8sP}e}$i$v/iE(RC=lXc|GyR4Ts+X-u-YP.Pw6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'msc_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
