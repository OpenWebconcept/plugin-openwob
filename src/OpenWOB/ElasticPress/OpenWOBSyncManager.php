<?php declare(strict_types=1);

namespace Yard\OpenWOB\ElasticPress;

use ElasticPress\Indexable\Post\SyncManager;
use ElasticPress\Indexables as Indexables;

class OpenWOBSyncManager extends SyncManager
{
    /**
	 * Indexable slug
	 *
	 * @since  3.0
	 * @var    string
	 */
	public $indexable_slug = 'openwob-item';
}
