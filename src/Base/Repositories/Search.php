<?php

namespace OWC\OpenPub\Base\Repositories;

use WP_REST_Request;

class Search extends Item
{
    protected $posttype = 'openpub-item';

    protected static $globalFields = [];

    /** @var WP_REST_Request */
    protected $request;

    /** @var array */
    protected $whitelist = ['s', 'posts_per_page'];

    /** @throws \ReflectionException|\OWC\OpenPub\Base\Exceptions\PropertyNotExistsException */
    public function __construct(WP_REST_Request $request)
    {
        parent::__construct();

        $this->request = $request;
    }

    /**
     * Add additional query arguments.
     */
    public function query(array $args): AbstractRepository
    {
        $this->queryArgs = array_merge($this->queryArgs, $args);
        $this->queryArgs = array_merge($this->queryArgs, $this->addSearchParameters());

        return $this;
    }

    private function addSearchParameters(): array
    {
        return [
            'post_type'      => 'any',
            'ep_integrate'   => true,
            'posts_per_page' => $this->perPage(),
            's'              => $this->sanitizeSearch()
        ];
    }

    protected function sanitizeSearch(): string
    {
        return sanitize_text_field($this->request->get_param('s'));
    }

    protected function perPage(): int
    {
        $amount = $this->request->get_param('posts_per_page') ?? 10;

        $amount = absint($amount);

        /**
         * Change the maximum number of posts per page
         *
         * @param int $max Maximum posts per page.
         */
        $max = (int)10;
        if ((int)$max < $amount) {
            $amount = $max;
        }

        return $amount;
    }
}
