<?php declare(strict_types=1);

namespace Yard\OpenWOB\RestAPI\Filters;

class FactoryFilter
{
    /** @var string */
    protected $filter;

    /** @var [] */
    protected $value;

    private function __construct($filter)
    {
        [$this->filter, $this->value] = $this->extractFilter($filter);
    }

    public static function resolve($filter): self
    {
        return new static($filter);
    }

    protected function extractFilter($filter): array
    {
        if (false !== \strpos($filter, '=')) {
            return explode('=', $filter);
        }
        if (false !== \strpos($filter, ':')) {
            return explode(':', $filter);
        }
        return [$filter, ''];
    }

    public function get()
    {
        $class = '\\Yard\\OpenWOB\\RestAPI\\Filters\\' . ucfirst($this->filter) .'Filter';
        if (!class_exists($class)) {
            return new NullFilter($this->value);
        }
        return new $class($this->value);
    }
}
