<?php

/*
* Copyright 2015 Litarvan & Vavaballz
*
* This file is part of OpenAuth.

* OpenAuth is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* OpenAuth is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public License
* along with OpenAuth.  If not, see <http://www.gnu.org/licenses/>.
*/

// Importing all the core classes
require 'core/Database.php';
require 'core/Queries.php';
require 'core/Config.php';
require 'core/functions.php';

// Creating an array with all the request informations
$args = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']), "/");
$request['args'] = (!empty($args)) ? explode("/", $args) : false;
$request['method'] = $_SERVER['REQUEST_METHOD'];
$request['content-type'] = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;


// If the config file already exists
if(file_exists('config.php'))
	// If the install page doesn't exist
	if(!file_exists('install.php'))
		// If we are in the home page (no arguments given)
		if(empty($request['args'][0])) {
			// Creating an array with the app informations
			$infos = array(
				'Status'					=>	'OK',
				'Runtime-Mode'				=>	'productionMode',
				'Application-Author' 		=>	'Litarvan & Vavaballz',
				'Application-Description'	=>	'OpenAuth Server.',
				'Specification-Version'		=>	'1.0.0-SNAPSHOT',
				'Application-Name'			=>	'openauth.server',
				'Implementation-Version' 	=>	'1.0.0_build01',
				'Application-Owner' 		=>	Core\Config::get('authinfos.owner'),
			);

			// And printing it as a JSON
			echo json_encode($infos);
		}

		// If the url is authenticate and there is no more arguments
		elseif($request['args'][0] == "authenticate" && empty($request['args'][1])) {
			// Setting the content-type to JSON
			header('Content-Type: application/json');

			// Printing the authenticate page
			require 'app/authenticate.php';
		}

		// If the url is refresh and there is no more arguments
		elseif($request['args'][0] == "refresh" && empty($request['args'][1])) {
			// Setting the content-type to JSON
			header('Content-Type: application/json');

			// Printing the refresh page
			require 'app/refresh.php';
		}

		// If the url is signout and there is no more arguments
		elseif($request['args'][0] == "signout" && empty($request['args'][1])) {
			// Setting the content-type to JSON
			header('Content-Type: application/json');

			// Printing the logout page
			require 'app/logout.php';
		}

		// If the url is validate and there is no more arguments
		elseif($request['args'][0] == "validate" && empty($request['args'][1])) {
			// Setting the content-type to JSON
			header('Content-Type: application/json');

			// Printing the logout page
			require 'app/validate.php';
		}

		// If the url is invalidate and there is no more arguments
		elseif($request['args'][0] == "invalidate" && empty($request['args'][1])) {
			// Setting the content-type to JSON
			header('Content-Type: application/json');

			// Printing the logout page
			require 'app/invalidate.php';
		}

		// If the url is register and there is no more arguments
		elseif($request['args'][0] == "register" && empty($request['args'][1]))
			// If the register page is activated in the config
			if(Core\Config::get('activeRegisterPage'))
				// Printing the register page
				require 'app/register.php';

			// Else if the register page is disabled
			else {
				// Setting the header to 404 error
				header("HTTP/1.0 404 Not Found");

				// Printing the first error
				echo error(1);
			}

		// Else if the request is just unknown
		else {
			// Setting the header to 404 error
			header("HTTP/1.0 404 Not Found");

			// Printing the first error
			echo error(1);
		}

	// Else if the install page exists
	else {
		// Deleting it
		unlink("install.php");

		// And redirecting to the index
		header("Location: .");
	}

// Else if the config doesn't exists
else
	// Printing the install page
	require 'install.php';
