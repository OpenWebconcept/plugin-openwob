<?php declare(strict_types=1);

namespace Yard\OpenWOB\ElasticPress;

use Yard\OpenWOB\Foundation\Config;
use Yard\OpenWOB\Repository\OpenWOBRepository;

class ElasticPress
{
    /**
     * @var \Yard\OpenWOB\Foundation\Config
     */
    private $config;

    /**
     * @var \Yard\OpenWOB\Repository\OpenWOBRepository
     */
    protected $repository;

    public function __construct(Config $config, OpenWOBRepository $repository)
    {
        $this->config = $config;
        $this->repository = $repository;
    }

    public function setFilters(): void
    {
        /**
         * Search settings
         */
        \add_filter('formattedArgs', [$this, 'setFormattedArgs'], 11, 2);
        \add_filter('epwr_decay', [$this, 'setDecay'], 10, 3);
        \add_filter('epwr_offset', [$this, 'setOffset'], 10, 3);
        \add_filter('ep_analyzer_language', [$this, 'setLanguage'], 10, 2);
    }

    /**
     * Set decay of post.
     */
    public function setDecay(int $decay, array $formatted_args, array $args): int
    {
        return $this->config->get('elasticpress.expire.decay');
    }

    /**
     * Set offset of the decay of post.
     */
    public function setOffset(string $decay, array $formatted_args, array $args): string
    {
        return $this->config->get('elasticpress.expire.offset');
    }

    /**
     * Weight more recent content in searches.
     */
    public function setFormattedArgs(array $formattedArgs, array $args): array
    {
        // Move the existing query.
        $existing_query = $formattedArgs['query'];
        unset($formattedArgs['query']);
        $formattedArgs['query']['function_score']['query'] = $existing_query;

        /**
         * Add filter matches that will weight the results.
         *
         * Use any combination of filters here, any matched filter will adjust the weighted results
         * according to the scoring settings set below. This example pseudo code below matches a custom term with the current or a parent item.
         */
        $formattedArgs['query']['function_score']['functions'] = [

            // The current item gets a weight of 3.
            [
                "filter" => [
                    "match" => [
                        "post_title" => \get_query_var('s'),
                    ],
                ],
                "weight" => $this->config->get('elasticpress.search.weight'),
            ],
        ];

        // Specify how the computed scores are combined.
        $formattedArgs['query']['function_score']["score_mode"] = "sum";
        $formattedArgs['query']['function_score']["boost_mode"] = "multiply";

        return $formattedArgs;
    }

    /**
     * Set the language for the ES instance.
     */
    public function setLanguage(string $language, $analyzer): string
    {
        return $this->config->get('elasticpress.language');
    }

    /**
     * Define all the necessary settings.
     */
    public function setSettings(): void
    {
        $settings = $this->getSettings();

        if (isset($settings['_ow_setting_elasticsearch_url']) && (!defined('EP_HOST'))) {
            if (isset($settings['_ow_setting_elasticsearch_shield']) && (!defined('ES_SHIELD'))) {
                define('ES_SHIELD', trim($settings['_ow_setting_elasticsearch_shield']));
            }

            $url = parse_url($settings['_ow_setting_elasticsearch_url']);
            $build[] = $url['scheme'] . '://';
            $build[] = defined('ES_SHIELD') ? sprintf('%s@', ES_SHIELD) : '';
            $build[] = $url['host'];
            $build[] = !empty($url['port']) ? sprintf(':%s', $url['port']) : '';
            $build[] = !empty($url['path']) ? sprintf('/%s', ltrim($url['path'], '/')) : '/';

            define('EP_HOST', implode('', (array_filter($build))));

            \update_option('ep_host', EP_HOST);
        }

        if (isset($settings['_ow_setting_elasticsearch_prefix']) && (!defined('EP_INDEX_PREFIX'))) {
            define('EP_INDEX_PREFIX', $settings['_ow_setting_elasticsearch_prefix']);
        }
    }

    public function getSettings(): array
    {
        return \get_option('_ow_openwob_settings', []);
    }
}
