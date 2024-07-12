%[kind : conf]
%[file : Route-%%(self.obName.title())%%.php] 
%[path : Config/Routes-Api]
<?php
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->resource('%%(self.obName.lower())%%s', ['controller' => '%%(self.obName.title())%%']);
});
