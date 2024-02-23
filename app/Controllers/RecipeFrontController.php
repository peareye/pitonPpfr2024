<?php

/**
 * PPFR Custom Front Controller
 *
 * @link      https://github.com/peareye/pitonPpfr2024
 * @copyright Copyright 2024 Wolfgang Moritz
 */

declare(strict_types=1);

namespace PitonCMS\Controllers;

use Piton\Controllers\FrontController;
use PitonCMS\Models\RecipePageMapper;
use Slim\Http\Response;
use Throwable;
use Exception;

/**
 * PPFR Custom Front Controller
 *
 */
class RecipeFrontController extends FrontController
{
    /**
     * PPRF Legacy URL Handler
     *
     * Responds to requests to old URL structure
     * - 404 Not Found if the recipe does not exist
     * - 301 Permanent Redirect to the new recipe url
     */
    public function ppfrLegacyUrl(array $args): Response
    {
            // Get dependencies
            $RecipePageMapper = ($this->container->dataMapper)('RecipePageMapper', 'PitonCMS\\Models\\');

            // Sanitize input and request page data
            $slug = htmlspecialchars(strtolower($args['slug']));
            $page = $RecipePageMapper->findRecipeByPageSlug($args['slug']);

            // If empty, return 404
            if (empty($page)) {
                return $this->notFound();
            }

            // If not empty, return 301 redirect to new recipe
            return $this->redirect('showPage', ['slug1' => $page->collection_slug, 'slug2' => $page->page_slug], 301);
    }
}
