<?php

namespace Yard\OpenWOB\RestAPI\Filters;

class NullFilter extends AbstractFilter
{
    public function getQuery(): array
    {
        return [];
    }
}
