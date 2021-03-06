<?php 


define ('TEST_BASE_DIR', __DIR__.'/');
define ('TEST_CODEBASE_DIR', '../');

define ('TEST_FIXTURE_DIR', 'Fixture/');
define ('TEST_TMP_DIR', 'Tmp/');
define ('TEST_TMP_URL', 'http://localhost/ip3.x/phpunit/Tmp/');
define ('TEST_UNWRITABLE_DIR', '/var/tmp/testDir'); //root owned empty dir which can't be writable by Apache process and can't be chmoded. Used by making symbolic links and emulating unwritable dirs in such way.

define ('TEST_DB_HOST', 'localhost');
define ('TEST_DB_USER', 'test');
define ('TEST_DB_PASS', 'test');
define ('TEST_DB_NAME', 'test');

define ('TEST_CAPTURE_SCREENSHOT_ON_FAILURE', true);
define ('TEST_SCREENSHOT_PATH', '/var/www/ImpressPagesTestScreenshot/');
define ('TEST_SCREENSHOT_URL', 'http://localhost/ImpressPagesTestScreenshot');

define('RECENT_VERSION', '3.4');