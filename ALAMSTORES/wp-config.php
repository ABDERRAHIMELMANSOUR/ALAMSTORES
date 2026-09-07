<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 */

// ** Database settings ** //
define( 'DB_NAME', 'alamstor_alamstores_wp' );
define( 'DB_USER', 'alamstor_alamstores_user' );
define( 'DB_PASSWORD', 'VK3DZoJMbD' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         'ccqnzrsep5dgnccxg2kskek85j1ql6dbcmqma3zuuytyswn7t1hxiid6erdm6iai' );
define( 'SECURE_AUTH_KEY',  'ydpd8b1t0ta8ftlxef1pw7yxynu51tuzx9qotgjnyrnagvihekuenca4d7uqzwvj' );
define( 'LOGGED_IN_KEY',    'hxwgjmk4vmtzftezfboa1zianw0poju9uof9evyilrymez43zco7gdc9jl5ki8ai' );
define( 'NONCE_KEY',        'bvkl0mmmzud5qyxsxe64nxtjbzrzygke0lvaie2bcsn6pku8wstgjf29p3wpbero' );
define( 'AUTH_SALT',        'bylyw6eele5q9ycfxfvdpnwpodqfv54cahsyv4cthebw7jazo5yono0wlmpsp4qm' );
define( 'SECURE_AUTH_SALT', 'qwyabirfctj40rc5sqqwgntuuori4hbmjucfda6uplkacv9zm38elgj6qliuyfai' );
define( 'LOGGED_IN_SALT',   'wxw3zwlrgomupotmr6ziclcncvyxat1fbvp4mgonvau0hcu3u6vygbvue8przady' );
define( 'NONCE_SALT',       'al8duzrblho0cxbospunwhisayr4uanskzx7wzaoicscvhlx9y2hqjprgu7ewokh' );
/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wpnq_';

/**
 * For developers: WordPress debugging mode.
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';