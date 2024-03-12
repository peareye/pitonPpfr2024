<?php

/**
 * PPFR Custom Recipe Collection Mapper
 *
 * @link      https://github.com/peareye/pitonPpfr2024
 * @copyright Copyright 2024 Wolfgang Moritz
 */

declare(strict_types=1);

namespace PitonCMS\Models;

use Piton\Models\CollectionMapper;

/**
 * PPFR Recipe Collection Mapper
 */
class RecipeCollectionMapper extends CollectionMapper
{
    /**
     * Get Collection Slugs
     *
     * @param void
     * @return array|null
     */
    public function getCollectionSlugs(): ?array
    {
        $this->sql = <<<SQL
select id, collection_slug
from collection;
SQL;

        return $this->find();
    }
}
