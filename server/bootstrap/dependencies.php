<?php
/*
* Copyright 2015 Vavaballz
*
* This file is part of OpenAuth-Server V2.
* OpenAuth-Server V2 is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* OpenAuth-Server V2 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public License
* along with OpenAuth-Server V2.  If not, see <http://www.gnu.org/licenses/>.
*/

// DIC configuration
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('views/', [
        'cache' => false
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    return $view;
};

// monolog
//$container['logger'] = function ($c) {
//    $settings = $c->get('settings')['logger'];
//    $logger = new Monolog\Logger($settings['name']);
//    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
//    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
//    return $logger;
//};

//Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return error(1, $c['response']);
    };
};

if (isset($container->get('settings')['db'])) {
    //Eloquent
    $capsule = new Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container->get('settings')['db']);
    $capsule->bootEloquent();
}
