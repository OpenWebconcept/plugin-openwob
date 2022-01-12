<?php declare(strict_types=1);

/**
 * BasePlugin which sets all the serviceproviders.
 */

namespace Yard\OpenWOB\Foundation;

/**
 * BasePlugin which sets all the serviceproviders.
 */
class Plugin
{

    /**
     * Name of the plugin.
     *
     * @var string
     */
    const NAME = OW_SLUG;

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @var string VERSION
     */
    const VERSION = OW_VERSION;

    /**
     * Path to the root of the plugin.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Instance of the configuration repository.
     *
     * @var \Yard\OpenWOB\Foundation\Config
     */
    public $config;

    /**
     * Instance of the Hook loader.
     *
     * @var Loader
     */
    public $loader;

    /**
     * Constructor of the BasePlugin
     *
     * @param string $rootPath
     *
     * @return void
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        load_plugin_textdomain($this->getName(), false, $this->getName() . '/languages/');

        $this->loader = new Loader;

        $this->config = new Config($this->rootPath . '/config');
        $this->config->setProtectedNodes(['core']);
    }

    /**
     * Boot the plugin.
     *
     * @hook plugins_loaded
     *
     * @return bool
     */
    public function boot(): bool
    {
        $dependencyChecker = new DependencyChecker(
            $this->config->get('dependencies.required'),
            $this->config->get('dependencies.suggested'),
            new DismissableAdminNotice
        );

        if ($dependencyChecker->hasFailures()) {
            $dependencyChecker->notifyFailed();
            \deactivate_plugins(plugin_basename(OW_FILE));

            return false;
        }

        if ($dependencyChecker->hasSuggestions()) {
            $dependencyChecker->notifySuggestions();
        }

        // Set up service providers
        $this->callServiceProviders('register');

        if (\is_admin()) {
            $this->callServiceProviders('register', 'admin');
            $this->callServiceProviders('boot', 'admin');
        }

        if ('cli' === php_sapi_name()) {
            $this->callServiceProviders('register', 'cli');
            $this->callServiceProviders('boot', 'cli');
        }

        $this->callServiceProviders('boot');

        // Register the Hook loader.
        $this->loader->addAction('init', $this, 'filterPlugin', 4);
        $this->loader->register();

        return true;
    }

    /**
     * Allows for hooking into the plugin name.
     *
     * @return void
     */
    public function filterPlugin()
    {
        \do_action('yard/' . self::NAME . '/plugin', $this);
    }

    /**
     * Call method on service providers.
     *
     * @throws \Exception
     */
    public function callServiceProviders(string $method, string $key = ''): void
    {
        $offset = $key ? "core.providers.{$key}" : 'core.providers';
        $services = $this->config->get($offset);

        foreach ($services as $service) {
            if (is_array($service)) {
                continue;
            }

            $service = new $service($this);

            if (! $service instanceof ServiceProvider) {
                throw new \Exception('Provider must be an instance of ServiceProvider.');
            }

            if (method_exists($service, $method)) {
                $service->$method();
            }
        }
    }

    /**
     * Get the name of the plugin.
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * Get the version of the plugin.
     */
    public function getVersion(): string
    {
        return static::VERSION;
    }

    /**
     * Return root path of plugin.
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}
