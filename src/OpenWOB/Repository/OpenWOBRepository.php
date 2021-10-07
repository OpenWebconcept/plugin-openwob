<?php declare(strict_types=1);

namespace Yard\OpenWOB\Repository;

use Yard\OpenWOB\Models\OpenWOB as OpenWOBModel;

/**
 * @OA\Schema(schema="repository")
 */
class OpenWOBRepository extends Base
{
    protected $posttype = 'openwob-item';

    /** @inheritdoc */
    protected $model = OpenWOBModel::class;

    protected static $globalFields = [];

    /**
     * Add additional query arguments.
     */
    public function query(array $args): self
    {
        $this->queryArgs = array_merge($this->queryArgs, $args);

        return $this;
    }
}
