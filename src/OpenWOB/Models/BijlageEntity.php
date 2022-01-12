<?php declare(strict_types=1);

namespace Yard\OpenWOB\Models;

class BijlageEntity extends AbstractEntity
{
    /** @var array */
    protected $required = ['Titel_Bijlage', 'URL_Bijlage'];

    public function getTime(): string
    {
        $date = (int) $this->data[self::PREFIX . 'Tijdstip_laatste_wijziging_bijlage']['timestamp'] ?? date('now');

        return (new \DateTime())->setTimestamp($date)->setTimezone(new \DateTimeZone("UTC"))->format("Y-m-d\TH:i:s");
    }

    protected function data(): array
    {
        return [
            'Type_Bijlage'                       => $this->data[self::PREFIX . 'Type_Bijlage'] ?? '',
            'Status_Bijlage'                     => $this->data[self::PREFIX . 'Status_Bijlage'] ?? '',
            'Tijdstip_laatste_wijziging_bijlage' => $this->getTime() ?? '',
            'Titel_Bijlage'                      => $this->data[self::PREFIX . 'Titel_Bijlage'] ?? '',
            'URL_Bijlage'                        => $this->data[self::PREFIX . 'URL_Bijlage'] ?? '',
        ];
    }
}
