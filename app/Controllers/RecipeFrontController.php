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
use Psr\Http\Message\ResponseInterface as Response;

/**
 * PPFR Custom Front Controller
 */
class RecipeFrontController extends FrontController
{
    /**
     * PPRF Legacy URL Handler
     *
     * Responds to requests to old URL structure
     * - 404 Not Found if the recipe does not exist
     * - 301 Permanent Redirect to the new recipe url
     * @param array $args Array of URL parameters, expecting 'id', 'slug'
     * @return Response
     */
    public function ppfrLegacyUrl(array $args): Response
    {
            // Get dependencies
            $recipePageMapper = ($this->container->get('dataMapper'))('RecipePageMapper', 'PitonCMS\\Models\\');

            // Sanitize input and request page data
            $slug = htmlspecialchars(strtolower($args['slug']), ENT_QUOTES);
            $page = $recipePageMapper->findRecipeByPageSlug($slug);

            // If empty, throw 404
            if (empty($page)) {
                $this->notFound();
            }

            // If not empty, return 301 permanent redirect to new recipe location
            return $this->redirect('showPage', ['slug1' => $page->collection_slug, 'slug2' => $page->page_slug], 301);
    }

    /**
     * Category or Collection Landing Page
     *
     * Accepts requests for a list of recipes, and disambiguates between Collections and Categories
     * Collection slugs are prioritized over Category requests with the same string
     * Unknown collections or categories will be rejected and return a 404
     * @param array $args Array of URL parameters, expecting 'type'
     * @return Response
     * @throws Exception NotFound
     */
    public function collectionOrCategoryLandingPage(array $args): Response
    {
        // Get dependencies, start with current collections. Pagination results per page relies on $config setting
        $recipeCollectionMapper = ($this->container->get('dataMapper'))('RecipeCollectionMapper', 'PitonCMS\\Models\\');
        $recipePageMapper = ($this->container->get('dataMapper'))('RecipePageMapper', 'PitonCMS\\Models\\');
        $pagination = $this->getPagination();

        // Get list of approved categories from Site Settings, and reduce array to just the slugs as keys and ID as values
        $collections = $recipeCollectionMapper->getCollectionSlugs();
        $collections = array_combine(array_column($collections, 'collection_slug'), array_column($collections, 'id'));

        // If the requested type is a collection, return the collection landing page
        if (array_key_exists($args['type'], $collections)) {
            $collectionPages = $recipePageMapper->findPublishedCollectionPagesById($collections[$args['type']], $pagination->getLimit(), $pagination->getOffset());
            $pagination->setTotalResultsFound($recipePageMapper->foundRows() ?? 0);

            // Add to extension data to be picked up in showPage()
            if ($collectionPages) {
                $this->setExtensionPageData('resultsCollectionOrCategoryName', $args['type']);
                $this->setExtensionPageData('results', $collectionPages);
            }

            // Return rendered page. Hard coding the PPFR 'recipes' landing page slug. End
            return $this->showPage(['slug1' => 'recipes']);
        }

        // Now test for categories

        // Get and match against approved categories from site settings
        if (isset($this->settings['site']['ppfrCategories']) && in_array($args['type'], explode(',', $this->settings['site']['ppfrCategories']))) {
            $categoryPages = $recipePageMapper->findRecipesByCategory($args['type'], $pagination->getLimit(), $pagination->getOffset());
            $pagination->setTotalResultsFound($recipePageMapper->foundRows() ?? 0);

            // Set to extension data to be picked up in showPage()
            if ($categoryPages) {
                $this->setExtensionPageData('resultsCollectionOrCategoryName', $args['type']);
                $this->setExtensionPageData('results', $categoryPages);
            }

            // Return rendered page. Hard coding the PPFR 'recipes' landing page slug. End
            return $this->showPage(['slug1' => 'recipes']);
        }

        // Nothing matched, throw 404
        $this->notFound();
    }
}
