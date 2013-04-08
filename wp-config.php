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
define('DB_NAME', 'apeborgu_wp1');

/** MySQL database username */
define('DB_USER', 'mastersa');

/** MySQL database password */
define('DB_PASSWORD', 'Kerrang!');

/** MySQL hostname */
define('DB_HOST', 'aws-rds-apeb.cff0v2jjph3v.eu-west-1.rds.amazonaws.com');

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

define('AUTH_KEY',         'lnEnbIl6ai8bSdO7lKQzlMsBPFIasFMuBwCDlBVNgxN9JpRAErGNjvrntHl2nZBP');
define('SECURE_AUTH_KEY',  'GdcqQ36GBN1LZouW2ZoGx9Q1zYFuIfqaEPkeJscq1bqzDiKIJ5No0LbwPoG6Z8J0');
define('LOGGED_IN_KEY',    'nUcrUCjTWBhrdk2hL7ZpqRCl3ljMtp1pCEFxketlzZAbICAbJJRGgaUH4juwhBOI');
define('NONCE_KEY',        '2aTg9MgUbnHW9ZFNyzNB2BNcCAjyf4Mf3ga523KZXnQep6cRIUiMB6GAJNZD4PF1');
define('AUTH_SALT',        'CRF2ujzvm01HlFQPwd7o1758g3loxGY5cmVJ0Bh8ySsyyiIH3uvRbKhosoIHEJUI');
define('SECURE_AUTH_SALT', 'GvHVnvFtAJiRn3GonZs8lGbv04POYIpZLlL6APVfwR4L2c23o13yDBwZN2eQvO1L');
define('LOGGED_IN_SALT',   'xAF9Rp4c3lVtPcbWz7U6myh81dKw5L8Z53z6OgRjdw6q0YjBeqfLByuNx0ArcvUS');
define('NONCE_SALT',       'lDHvVEZePGXHjGoCzcDbvg2PAUziMTsSbjthEbmFaxcwEnxoGT9koOB8MknuJu1U');

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
