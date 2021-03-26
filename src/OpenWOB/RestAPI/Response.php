<?php

namespace Yard\OpenWOB\RestAPI;

use WP_Query;
use WP_REST_Response;

/**
 * @OA\Schema()
 */
class Response extends WP_REST_Response
{
    /**
     * @OA\Property(
     *   property="WOBverzoeken",
     *   type="array",
     *   @OA\Items(ref="#/components/schemas/OpenWOB"),
     *   @OA\Link(link="OpenWOBRepository", ref="#/components/links/OpenWOBRepository")
     * )
     */
    public function __construct(array $data, WP_Query $query)
    {
        if (!$query->is_single) {
            $data = \array_merge_recursive(
                $data,
                $this->addPaginator($query),
                $this->getQuery($query)
            );
        }
        parent::__construct($data);
    }

    /**
     * @OA\Property(
     *   property="pagination",
     *   type="array",
     *   @OA\Items(
     *
     *   )
     * )
     */
    protected function addPaginator(WP_Query $query): array
    {
        $page = $query->get('paged');
        $page = (0 === $page) ? 1 : $page;

        return [
            'pagination' => [
                'total'                   => (int) $query->found_posts,
                'limit'                   => $query->get('posts_per_page'),
                'pages'                   => [
                    'total'              => $query->max_num_pages,
                    'current'            => $page,
                ]
            ]
        ];
    }

    /**
     * @OA\Property(
     *   property="query_parameters"
     * )
     */
    protected function getQuery(WP_Query $query): array
    {
        return [
            'query_parameters'        => $query->query
        ];
    }
}
