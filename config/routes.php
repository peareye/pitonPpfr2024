<?php

/**
 * PPFR Custom Public Application Routes
 *
 * @link      https://github.com/peareye/pitonPpfr2024
 * @copyright Copyright 2024 Wolfgang Moritz
 */

declare(strict_types=1);

use PitonCMS\Controllers\RecipeFrontController;

// Handle requests for legacy PPFR urls
$app->get('/recipe/show/{id:[0-9]+}/{slug:[a-zA-Z0-9-]+}', function ($request, $response, $args) {
    return (new RecipeFrontController($request, $response, $this))->ppfrLegacyUrl($args);
});

// Handle requests for Collection or Category landing page
$app->get('/recipes/{type:[a-z-]+}', function ($request, $response, $args) {
    return (new RecipeFrontController($request, $response, $this))->collectionOrCategoryLandingPage($args);
});
