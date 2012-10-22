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
define('DB_NAME', 'cefs_10per');

/** MySQL database username */
define('DB_USER', 'cefs_10per_admin');

/** MySQL database password */
define('DB_PASSWORD', 'ce013111JL');

/** MySQL hostname */
define('DB_HOST', 'mysql01.unity.ncsu.edu');

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
define('AUTH_KEY',         '(nFfHC(EInQz;7,,PEB.4h{oE7QKo`h9dW.=Es<BA0JK~M4CJT$Z&`)-Z~;4ek#b');
define('SECURE_AUTH_KEY',  'e4a04]Zb11$|9zdZThjt&V}?<<1R,5cn:|IB%h+e[3EiW6l5I}$:| 9<.qc0+Wdw');
define('LOGGED_IN_KEY',    '$chcZk&U{xs2sCr:38SyQL:Jf+sMdaoLqg-O+d,xEG]p=p>mI9r7Z|_!U*-KZ6Qn');
define('NONCE_KEY',        ']EiqC_(FK^EJXpYnPc`^U^4|lYW8r9LIGAW=#(w:b00~x/- -;GNxv3Ic]2a|m=f');
define('AUTH_SALT',        'P10?us?>,iLR _):B,;IPB^D^at7*/fZNmX,[mpqs06ZJNd4Zcjor~4q_[LkLh93');
define('SECURE_AUTH_SALT', '&PI|d`s]*bzhk|d.,T*4n)%70E_3T|c+$^114n7y7-rMv5r^3`H >!5BUXx@$pxw');
define('LOGGED_IN_SALT',   'l^X^Q{4jt-N}[%wU_7&E-_xW@7|xn.)#b&_]94?`eQHQGyu9/2(CkckCh>k3c&j)');
define('NONCE_SALT',       'd+ 6h:S2y|{m49jD)u5zG]a&3!#|v7yS[9u_vvtY_?m]3|H`Z5lnK-^C`2>@L9Bp');

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
