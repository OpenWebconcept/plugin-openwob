<?php declare(strict_types=1);

namespace Yard\OpenWOB\Models;

class InfoEntity extends AbstractEntity
{
    /** @var array */
    protected $required = ['Status', 'Tijdstip_laatste_wijziging'];

    public function getTime(): string
    {
        $date = (int) $this->data[self::PREFIX . 'Tijdstip_laatste_wijziging']['timestamp'];
        return (new \DateTime())->setTimestamp($date)->setTimezone(new \DateTimeZone("UTC"))->format("Y-m-d\TH:i:s");
    }

    protected function data(): array
    {
        return [
            'Status'                     => $this->data[self::PREFIX . 'Status'] ?? '',
            'Tijdstip_laatste_wijziging' => $this->getTime(),
        ];
    }
}
