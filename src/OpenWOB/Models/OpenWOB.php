<?php declare(strict_types=1);

namespace Yard\OpenWOB\Models;

use WP_Post;

/**
 * @OA\Schema(
 *   title="OpenWOB model",
 *   type="object"
 * )
 */
class OpenWOB
{
    /**
      * @var array
      */
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $this->cleanupData($data);
    }

    protected function cleanupData(array $data): array
    {
        $data['meta'] = array_filter(\get_post_meta($data['ID']), function ($key) {
            return !preg_match('/^_(.+)/', $key);
        }, ARRAY_FILTER_USE_KEY);

        $data['meta'] = array_map(function ($item) {
            if (is_array($item) and (1 === count($item))) {
                return \maybe_unserialize($item[0]);
            }
            return \maybe_unserialize($item);
        }, $data['meta']);

        return $data;
    }

    /**
     * Transform a single WP_Post item.
     */
    public function transform(): array
    {
        $data = [
            'Wobverzoek_informatie'       => InfoEntity::make($this->meta('Wobverzoek_informatie', []))->get(),
            'UUID'                        => $this->meta('UUID'),
            'ID'                          => $this->meta('ID'),
            'Behandelend_bestuursorgaan'  => $this->meta('Behandelend_bestuursorgaan'),
            'Ontvanger_informatieverzoek' => $this->meta('Ontvanger_informatieverzoek', ''),
            'Volgnummer'                  => $this->meta('Volgnummer', ''),
            'Titel'                       => $this->meta('Titel', ''),
            'Beschrijving'                => $this->field('post_content', ''),
            'Samenvatting'                => $this->field('post_excerpt', ''),
            'Verzoeker'                   => $this->meta('Verzoeker', ''),
            'Ontvangstdatum'              => $this->meta('Ontvangstdatum'),
            'Besluitdatum'                => $this->meta('Besluitdatum'),
            'Behandelstatus'              => $this->meta('Behandelstatus', ''),
            'Besluit'                     => $this->meta('Besluit'),
            'Termijnoverschrijding'       => $this->meta('Termijnoverschrijding', ''),
            'URL_informatieverzoek'       => $this->meta('URL_informatieverzoek'),
            'URL_inventarisatielijst'     => $this->meta('URL_inventarisatielijst', ''),
            'URL_besluit'                 => $this->meta('URL_besluit'),
            'Geografisch_gebied'          => $this->meta('Geografisch_gebied', ''),
            'BAG_ID'                      => $this->meta('BAG_ID', ''),
            'BGT_ID'                      => $this->meta('BGT_ID', ''),
            'Postcodegebied'              => $this->meta('Postcodegebied', ''),
        ];

        if ($coords = COORDSEntity::make($this->meta('COORDS', []))->get()) {
            $data['COORDS'] = $coords;
        }

        if ($geografischePositie = GeografischePositieEntity::make($this->meta('Geografische_positie', []))->get()) {
            $data['Geografische_positie'] = $geografischePositie;
        }

        foreach ($this->meta('Bijlagen', []) as $bijlage) {
            if (BijlageEntity::make($bijlage)->get()) {
                $data['Bijlagen'][] = BijlageEntity::make($bijlage)->get();
            }
        }

        foreach ($this->meta('Themas', []) as $thema) {
            if (ThemaEntity::make($thema)->get()) {
                $data['Themas'][] = ThemaEntity::make($thema)->get();
            }
        }

        return array_filter($data);
    }

    public function field(string $field, $default = null)
    {
        if (!array_key_exists($field, $this->data)) {
            return $default;
        }
        return trim($this->data[$field]);
    }

    public function meta(string $key, $default = null)
    {
        $separator = '.';
        $key = sprintf('%s_%s', 'wob', $key);
        $data = $this->data['meta'];
        // @assert $key is a non-empty string
        // @assert $data is a loopable array
        // @otherwise return $default value
        if (!is_string($key) || empty($key) || !count($data)) {
            return $default;
        }

        // @assert $key contains a dot notated string
        if (false !== strpos($key, $separator)) {
            $keys = array_map(function ($key) {
                if (!preg_match('/^wob_/', $key)) {
                    return sprintf('%s_%s', 'wob', $key);
                }
                return $key;
            }, explode($separator, $key));

            foreach ($keys as $innerKey) {
                // @assert $data[$innerKey] is available to continue
                // @otherwise return $default value
                if (!array_key_exists($innerKey, $data)) {
                    return $default;
                }

                $data = $data[$innerKey];
            }
            return $data;
        }

        // @fallback returning value of $key in $data or $default value
        return $data[$key] ?? $default;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
