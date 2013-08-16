<?php

namespace session\config;

// Memcache constants.
define('MEMCACHE_HOST', 'localhost');
define('MEMCACHE_PORT', 11211);

// MySQL constants.
define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');

// Salt length.
define('SALT_LENGTH', 16);

// Valid pages.
define('PAGES', serialize(array('account', 'login', 'password', 'signup')));
