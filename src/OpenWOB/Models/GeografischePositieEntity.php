<?php declare(strict_types=1);

namespace Yard\OpenWOB\Models;

class GeografischePositieEntity extends AbstractEntity
{
    /** @var array */
    protected $required = ['Longitude', 'Lattitude'];

    protected function data(): array
    {
        if (empty($this->data)) {
            return [];
        }

        return [
            'Longitude' => (float) $this->data[self::PREFIX . 'Longitude'] ?? '',
            'Lattitude' => (float) $this->data[self::PREFIX . 'Lattitude'] ?? '',
        ];
    }
}
