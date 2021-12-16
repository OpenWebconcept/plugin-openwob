<?php

declare(strict_types=1);

/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Load all stubs.
 */
$files = glob(__DIR__ . '/../Stubs/WordPress/*.php');
array_map(function ($file) {
    require_once $file;
}, $files);

define('WP_PLUGIN_DIR', __DIR__);
define('WP_DEBUG', false);
define('OW_FILE', __FILE__);
define('OW_SLUG', basename(__FILE__, '.php'));
define('OW_LANGUAGE_DOMAIN', OW_SLUG);
define('OW_DIR', basename(__DIR__));
define('OW_ROOT_PATH', __DIR__);
define('OW_VERSION', '1.0.5');

/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork(true);
\WP_Mock::bootstrap();

$GLOBALS[OW_LANGUAGE_DOMAIN] = [
    'active_plugins' => [OW_DIR . '/' . OW_FILE],
];

if (!function_exists('get_echo')) {

    /**
     * Capture the echo of a callable function.
     *
     * @param Callable $callable
     * @param array $args
     *
     * @return string
     */
    function get_echo(callable $callable, $args = []): string
    {
        ob_start();
        call_user_func_array($callable, $args);

        return ob_get_clean();
    }
}
