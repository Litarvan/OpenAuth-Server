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

// Routes
use App\Controller\ApiController;
use App\Controller\AppController;
use App\Controller\InstallController;

// Home
$app->get('/', AppController::class . ':home')
    ->setName("home");

// Install
$app->get('/install', InstallController::class . ":install")
    ->setName("install");
$app->post('/install', InstallController::class . ":install_post")
    ->setName("install_post");

// API
$app->post('/api/register', ApiController::class . ':register')
    ->setName("api.register");
$app->post('/api/authenticate', ApiController::class . ':authenticate')
    ->setName("api.authenticate");
$app->post('/api/refresh', ApiController::class . ':refresh')
    ->setName("api.refresh");
$app->post('/api/validate', ApiController::class . ':validate')
    ->setName("api.validate");
$app->post('/api/signout', ApiController::class . ':signout')
    ->setName("api.signout");
$app->post('/api/invalidate', ApiController::class . ':invalidate')
    ->setName("api.invalidate");