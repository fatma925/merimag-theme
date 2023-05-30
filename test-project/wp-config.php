<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test-project' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'U06fqpqc4pwtJ8dtWcGJwyfchQOofUT91AL5erYNgpkwKb5Ja5VU4A8woYCS5mgg' );
define( 'SECURE_AUTH_KEY',  'xL6ljDik3UYfUiY7r38A8TicP2v6qa6lmygujHS9RfTl23ZQ1twmCHsD34aTR3aW' );
define( 'LOGGED_IN_KEY',    'xMf2eVjgXgy8G84fVKv3NKz63tAdwnU4sOGzubV5pV4JWAnz7T9iWy1DLtj7Wgk3' );
define( 'NONCE_KEY',        'xNpI0dkbSD0YsV0oqsSusBQYfUFkCAKLx58EFEYZXBc8S0LibjLegqzEuTtj2tv0' );
define( 'AUTH_SALT',        'DiABgrSKXXVCynVqgoU4z6SqYL6c5pAESM0h2WAhvlVd41Mn6gkAknbJWBuU0RP9' );
define( 'SECURE_AUTH_SALT', 'WCa1acjMpTrq2nmOgaPcHG3RQAY7HE7B6TCw0Ozq6Bhk9cViybqhLRDh9mQ26jRx' );
define( 'LOGGED_IN_SALT',   'FIbifnIyyl5ZwdOj7eVaKEQrTa2yewhZGjJtqS49GupREmtLd9Y9542rvIfpXaV6' );
define( 'NONCE_SALT',       'lYRxWUh7D0iXeQ3bvStKDJ50MChpibNEQfDUi2lIh32OKJ99VeRxrTJfCE218hA2' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
