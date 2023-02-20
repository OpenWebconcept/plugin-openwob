<?php declare(strict_types=1);

namespace Yard\OpenWOB\ElasticPress;

use ElasticPress\Indexables;
use Yard\OpenWOB\Foundation\ServiceProvider;
use Yard\OpenWOB\Repository\OpenWOBRepository;

class ElasticPressServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @throws Exception
     */
    public function register()
    {
		if (\is_plugin_active('yard-elasticsearch/yard-elasticsearch.php')) {
			return;
		}

        if (! class_exists('\ElasticPress\Elasticsearch')) {
            return;
        }

        Indexables::factory()->register(new OpenWOBIndexable(new OpenWOBRepository, $this->plugin->config));

        add_filter('ep_dashboard_indexable_labels', function ($labels) {
            $labels['openwob'] = [
                'singular' => esc_html__('openwob-item', 'elasticpress'),
                'plural'   => esc_html__('openwob-items', 'elasticpress'),
            ];

            return $labels;
        });

        $elasticPress = new ElasticPress($this->plugin->config, new OpenWOBRepository);
        $this->plugin->loader->addAction('init', $elasticPress, 'setSettings', 10, 1);
        $this->plugin->loader->addAction('init', $elasticPress, 'setFilters', 10, 1);
    }
}
