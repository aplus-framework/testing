<?php
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Framework\Routing\RouteCollection;
use Framework\Routing\Router;

return [
    'default' => [
        'callback' => static function (Router $router) : void {
            $router->serve('http://localhost', static function (RouteCollection $routes) : void {
                $routes->get('/error', static function () : void {
                    throw new LogicException('Error in route action');
                });
            });
        },
    ],
];
