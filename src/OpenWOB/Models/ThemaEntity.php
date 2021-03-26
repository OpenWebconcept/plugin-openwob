<?php

namespace Yard\OpenWOB\Models;

class ThemaEntity extends AbstractEntity
{
    /** @var array */
    protected $required = ['Hoofdthema'];

    protected function data(): array
    {
        return [
            'Hoofdthema'        => $this->data[self::PREFIX . 'Hoofdthema'] ?? '',
            'Subthema'          => $this->data[self::PREFIX . 'Subthema'] ?? '',
            'Aanvullend_thema'  => $this->data[self::PREFIX . 'Aanvullend_thema'] ?? '',
        ];
    }
}
