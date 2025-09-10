%[kind : conf]
%[file : Route-%%(self.obName.title())%%.php] 
%[path : Config/Routes-Api/v1]
<?php
$routes->group('v1', ['namespace' => 'App\Controllers\Api\v1'], function($routes) {
    $routes->resource('%%(self.obName.lower())%%s', ['controller' => '%%(self.obName.title())%%']);
});
