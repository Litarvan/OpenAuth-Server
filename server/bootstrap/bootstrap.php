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

define("ROOT_PATH", dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';

// Helper
require __DIR__ . '/functions.php';

if(file_exists(root_path("bootstrap/after_install.php"))){
    require_once root_path("bootstrap/after_install.php");
}

session_start();

// Bootstrap
date_default_timezone_set("Europe/Paris");

// Instantiate the app
$settings_file = root_path("config/settings.php");
$settings = [];
if (file_exists($settings_file))
    $settings = require $settings_file;
$settings['settings']['displayErrorDetails'] = true;
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/dependencies.php';

// Register middleware
require __DIR__ . '/middleware.php';

// Register routes
require __DIR__ . '/routes.php';

// Run app
$app->run();