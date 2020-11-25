<?php

namespace OWC\OpenPub\Base\Repositories;

use Closure;
use OWC\OpenPub\Base\Exceptions\PropertyNotExistsException;
use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;
use WP_Query;

abstract class AbstractRepository
{
    protected $posttype;

    /**
     * Instance of the WP_Query object.
     *
     * @var null|WP_Query
     */
    protected $query = null;

    /**
     * Arguments for the WP_Query.
     *
     * @var array
     */
    protected $queryArgs = [];

    /**
     * Fields that need to be hidden.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Dynamically added fields.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Additional fields that needs to be added to an item.
     *
     * @var CreatesFields[]
     */
    protected static $globalFields = [];

    /**
     * Construct a new Model class.
     *
     * @throws PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function __construct()
    {
        /**
         * In order to register globalFields for a specific model the static property
         * "globalFields" must be present on the derived class to prevent adding fields to
         * the abstract Model class.
         */
        $reflect = new \ReflectionClass(static::class);
        if (__CLASS__ == $reflect->getProperty('globalFields')->class) {
            throw new PropertyNotExistsException(sprintf(
                'Property $globalFields must be present on derived class %s.',
                static::class
            ));
        }
    }

    /**
     * Get all the items from the database.
     *
     * @return array
     */
    public function all(): array
    {
        $args = array_merge($this->queryArgs, [
            'post_type' => [$this->posttype],
        ]);

        $this->query = new WP_Query($args);

        return array_map([$this, 'transform'], $this->getQuery()->posts);
    }

    /**
     * Find a particular openpub item by ID.
     *
     * @param int $id
     *
     * @return array|null
     */
    public function find(int $id)
    {
        $args = array_merge($this->queryArgs, [
            'p'         => $id,
            'post_type' => [$this->posttype],
        ]);

        $this->query = new WP_Query($args);

        if (empty($this->getQuery()->posts)) {
            return null;
        }

        return $this->transform(reset($this->getQuery()->posts));
    }

    /**
     * Find a particular pdc item by slug.
     *
     * @param string $slug
     *
     * @return array|null
     */
    public function findBySlug(string $slug)
    {
        $args = array_merge($this->queryArgs, [
            'name'        => $slug,
            'post_type'   => [$this->posttype],
        ]);

        $this->query = new WP_Query($args);

        if (empty($this->getQuery()->posts)) {
            return null;
        }

        return $this->transform(reset($this->getQuery()->posts));
    }

    /**
     * Get the WP_Query object.
     *
     * @return null|WP_Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Add additional query arguments.
     *
     * @param array $args
     *
     * @return $this
     */
    public function query(array $args)
    {
        $this->queryArgs = array_merge($this->queryArgs, $args);

        return $this;
    }

    /**
     * Returns the query args.
     *
     * @return array
     */
    public function getQueryArgs()
    {
        return $this->queryArgs;
    }

    /**
     * Hide a particular key from the request.
     *
     * @param array $keys
     *
     * @return $this
     */
    public function hide(array $keys)
    {
        $this->hidden = array_merge($this->hidden, $keys);

        return $this;
    }

    /**
     * Dynamically add additional fields to the model.
     *
     * @param string        $key
     * @param CreatesFields $creator
     *
     * @return $this
     */
    public function addField(string $key, CreatesFields $creator)
    {
        $this->fields[$key] = $creator;

        return $this;
    }

    /**
     * Adds a new field to the output.
     *
     * @param string        $key
     * @param CreatesFields $creator
     * @param Closure|null  $conditional
     *
     * @return void
     */
    public static function addGlobalField(string $key, CreatesFields $creator, Closure $conditional = null): void
    {
        static::$globalFields[] = [
            'key'         => $key,
            'creator'     => $creator,
            'conditional' => $conditional,
        ];
    }

    /**
     * Get all defined global fields of the Model.
     *
     * @return array
     */
    public static function getGlobalFields(): array
    {
        uasort(static::$globalFields, function ($a, $b) {
            return ($a['key'] < $b['key']) ? -1 : 1;
        });

        return static::$globalFields;
    }

    /**
     * Transform a single WP_Post item.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function transform(WP_Post $post)
    {
        $data = [
            'id'      => $post->ID,
            'title'   => $post->post_title,
            'content' => apply_filters('the_content', $post->post_content),
            'excerpt' => $post->post_excerpt,
            'date'    => $post->post_date,
            'slug'    => $post->post_name
        ];

        $data = $this->assignFields($data, $post);

        return $data;
    }

    /**
     * Assign fields to the data array.
     *
     * @param array   $data
     * @param WP_Post $post
     *
     * @return array
     */
    protected function assignFields(array $data, WP_Post $post)
    {
        // Assign global fields.
        foreach (static::getGlobalFields() as $field) {
            if (in_array($field['key'], $this->hidden)) {
                continue;
            }

            if (is_null($field['conditional'])) {
                // If the field has no conditional set we will add it
                $data[$field['key']] = $field['creator']->create($post);
            } else {
                // Check if the conditional matches.
                if ($field['conditional']($post)) {
                    $data[$field['key']] = $field['creator']->create($post);
                }
            }
        }

        // Assign dynamic fields.
        foreach ($this->fields as $key => $creator) {
            $data[$key] = $creator->create($post);
        }

        return $data;
    }
}
