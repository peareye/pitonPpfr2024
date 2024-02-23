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
use Piton\ORM\DataMapperAbstract;
use Exception;

/**
 * PPFR Recipe Page Mapper
 */
class RecipePageMapper extends PageMapper
{
    /**
     * Find Collection Page by Recipe Page Slug
     *
     * Finds page and collection
     * @param  string $recipeSlug Page slug
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

}
