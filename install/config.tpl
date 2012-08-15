<?php if(!defined('CMS_ROOT')) die;

// Database information:
// for SQLite, use sqlite:/tmp/frog.db (SQLite 3)
// The path can only be absolute path or :memory:
// For more info look at: www.php.net/pdo

// Database settings:
define('DB_DSN', '__DB_DSN__');
define('DB_USER', '__DB_USER__');
define('DB_PASS', '__DB_PASS__');
define('TABLE_PREFIX', '__TABLE_PREFIX__');

// Should CMS produce PHP error messages for debugging?
define('DEBUG', false);

// The directory name of your Frog CMS administration (you will need to change it manually)
define('ADMIN_DIR_NAME', 'admin');

// Change this setting to enable mod_rewrite. Set to "true" to remove the "?" in the URL.
// To enable mod_rewrite, you must also change the name of "_.htaccess" in your
// Frog CMS root directory to ".htaccess"
define('USE_MOD_REWRITE', __USE_MOD_REWRITE__);

// Add a suffix to pages (simluating static pages '.html')
define('URL_SUFFIX', '__URL_SUFFIX__');

// Set the timezone of your choice.
// Go here for more information on the available timezones:
// http://php.net/timezones
define('DEFAULT_TIMEZONE', 'Europe/Helsinki');

// Default system locale
define('DEFAULT_LOCALE', '__LANG__');