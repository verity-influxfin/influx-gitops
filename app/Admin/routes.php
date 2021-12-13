<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('banner', BannerController::class);
    $router->resource('count', CountController::class);
    $router->resource('knowledge_article', KnowledgeArticleController::class);
    $router->resource('milestone', MilestoneController::class);
    $router->resource('news', NewsController::class);
    $router->resource('event_campus_teams', EventCampusTeamController::class);
    $router->resource('event_campus_members', EventCampusMemberController::class);
    $router->resource('media', MediaController::class);
    $router->resource('partner', PartnerController::class);
    $router->resource('event_users', EventUsersController::class);
});
