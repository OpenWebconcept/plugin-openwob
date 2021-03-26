<?php

namespace Yard\OpenWOB\Models;

class COORDSEntity extends AbstractEntity
{
    /** @var array */
    protected $required = ['X', 'Y'];

    protected function data(): array
    {
        if (empty($this->data)) {
            return [];
        }

        return [
            'X' => (int) $this->data[self::PREFIX . 'X'] ?? '',
            'Y' => (int) $this->data[self::PREFIX . 'Y'] ?? '',
        ];
    }
}
