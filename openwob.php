<?php declare(strict_types=1);

/**
 * Plugin Name:       Yard | OpenWOB
 * Plugin URI:        https://www.yard.nl/
 * Description:       Adds OpenWOB implementation
 * Version:           1.0.7
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       openwob
 * Domain Path:       /languages
 */

use Yard\OpenWOB\Autoloader;
use Yard\OpenWOB\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

define('OW_FILE', __FILE__);
define('OW_SLUG', basename(__FILE__, '.php'));
define('OW_LANGUAGE_DOMAIN', OW_SLUG);
define('OW_DIR', basename(__DIR__));
define('OW_ROOT_PATH', __DIR__);
define('OW_VERSION', '1.0.7');

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new Autoloader();

if (file_exists(__DIR__ .'/vendor/autoload.php')) {
    require_once(__DIR__ .'/vendor/autoload.php');
}

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
    $plugin = (new Plugin(__DIR__))->boot();
}, 10);
