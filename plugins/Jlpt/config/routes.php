<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->plugin(
        'Jlpt',
        ['path' => '/jlpt'],
        function (RouteBuilder $builder) {
            $builder->setRouteClass(DashedRoute::class);
            $builder->connect('/', ['controller' => 'ManageSystem', 'action' => 'index']);
            $builder->fallbacks();
        }
    );
};
