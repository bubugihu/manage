<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->plugin(
        'Shipping',
        ['path' => '/shipping'],
        function (RouteBuilder $builder) {
            $builder->setRouteClass(DashedRoute::class);
            $builder->connect('/', ['controller' => 'Home', 'action' => 'index']);
            $builder->fallbacks();
        }
    );
};
