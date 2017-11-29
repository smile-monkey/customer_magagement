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
define('DB_NAME', 'wordpress_test');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '4ApD~OeU]C@|9pzobf96`vE4B5EGJPkci/Mn%T_|$7!IC9`xrF/IFH7:EDKPoMaD');
define('SECURE_AUTH_KEY',  '1hVW,~wq-{{VlisbRKO/Y8!;`9y?& U76f+SO#T.JBes5Rwg1BL>ZnS%M1UgqQTB');
define('LOGGED_IN_KEY',    'ASC/<m>w}xZjplA0H]OqZ>!Dz]7#9O)XoG*IFv+G3/;{@32m@%7ao&6Iz2#,8,l7');
define('NONCE_KEY',        '2aT7MF_$fDLdt5@h`L%6]rAyrDm|o$HX`T|bXnuN(1TAy=pHwuJ>]~UO$UZ0l~DX');
define('AUTH_SALT',        'Zp:zk3/Pv_8oPA3]*X?3E7>(Ze;@l!+Rhu[~&Q6Y8s1|R+(Mw46!2YdZ0|9w0y}v');
define('SECURE_AUTH_SALT', 'SF(*#JXE1A43[f4%ph {>CR6Z$8Z!~`:(8cY0l0*LjnKQYU;A*dSmEk1{`k/;s,c');
define('LOGGED_IN_SALT',   '*$)C0WyN3rW}BSNugP17441i5lGXl!iL%9+2nf)E@Y_?1#pDaLNE-BF-uLEy:$fY');
define('NONCE_SALT',       'Y.qQv_R#CW1V0k1kaB!5*!ZyPbc8C&>02W,[vg<^ksAtS`o.Tm?cCc_aOcQA.1YD');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
