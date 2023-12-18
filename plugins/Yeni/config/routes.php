<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->plugin(
        'Yeni',
        ['path' => '/yeni'],
        function (RouteBuilder $builder) {
            $builder->setRouteClass(DashedRoute::class);
            $builder->connect('/', ['controller' => 'Pages', 'action' => 'index']);
            $builder->fallbacks();
        }
    );
};
