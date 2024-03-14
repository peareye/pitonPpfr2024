<?php

/**
 * PPFR Custom Recipe Page Mapper
 *
 * @link      https://github.com/peareye/pitonPpfr2024
 * @copyright Copyright 2024 Wolfgang Moritz
 */

declare(strict_types=1);

namespace PitonCMS\Models;

use Piton\Models\PageMapper;
use Piton\Models\Entities\PitonEntity;

/**
 * PPFR Recipe Page Mapper
 */
class RecipePageMapper extends PageMapper
{
    /**
     * Find Collection Page by Recipe Page Slug
     *
     * Finds page and collection
     * @param string $recipeSlug Page slug
     * @return PitonEntity|null
     */
    public function findRecipeByPageSlug(string $recipeSlug): ?PitonEntity
    {
        $this->sql = <<<SQL
select c.*, p.*
from page p
join collection c on p.collection_id = c.id
where 1=1
and p.published_date <= '{$this->today}'
and p.page_slug = ?
SQL;

        $this->bindValues[] = $recipeSlug;

        return $this->findRow();
    }

    /**
     * Find Recipes by Category Page Setting
     *
     * Finds all recipes matching a category stored data_store from page settings
     * @param string $category Category name to search
     * @param  int   $limit
     * @param  int   $offset
     * @return array|null
     */
    public function findRecipesByCategory(string $category, int $limit = null, int $offset = null): ?array
    {
        $this->makeSelect();

        // Add search sub-query
        $this->sql .= <<<SQL
and p.published_date <= '{$this->today}'
and p.id in (
    select page_id
    from data_store
    where setting_key = 'subCategory'
    and setting_value like ?
)
order by p.published_date desc
SQL;

        $this->bindValues[] = "%$category%";

        // Add limit and offset if provided
        if ($limit) {
            $this->sql .= ' limit ?';
            $this->bindValues[] = $limit;
        }

        if ($offset) {
            $this->sql .= ' offset ?';
            $this->bindValues[] = $offset;
        }

        return $this->find(true, true);
    }
}
