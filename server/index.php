<?php 
/*
* Copyright 2015 TheShark34 & Vavaballz
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
require 'Core/Database.php';
require 'Core/Queries.php';
require 'Core/Config.php';
require 'Core/functions.php';

$request['args'] = explode('/', str_replace(dirname($_SERVER['SCRIPT_FILENAME'])."/", "", $_SERVER['DOCUMENT_ROOT'].substr($_SERVER['REQUEST_URI'], 1)));
$request['method'] = $_SERVER['REQUEST_METHOD'];
$request['content-type'] = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;


// Si le fichier de config existe on continue
// Sinon, on require install.php
if(file_exists('config.php')){
	// Si la page "install.php" n'existe pas on continue
	// Sinon, on la supprime
	if(!file_exists('install.php')){
		// Si on est sur l'accueil 
		if(empty($request['args'][0])){

			$infos = array(
				'Status'					=>	'OK',
				'Runtime-Mode'				=>	'productionMode',
				'Application-Author' 		=>	'TheShark34 & Vavaballz',
				'Application-Description'	=>	'OpenAuth Server.',
				'Specification-Version'		=>	'1.0.0',
				'Application-Name'			=>	'openauth.server',
				'Implementation-Version' 	=>	'1.0.0_build01',
				'Application-Owner' 		=>	Core\Config::get('authinfos.owner'),
			);

			echo json_encode($infos);

		// Si l'url est "authenticate" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "authenticate" && empty($request['args'][1])){

			header('Content-Type: application/json');
			require 'App/authenticate.php';

		// Si l'url est "refresh" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "refresh" && empty($request['args'][1])){

			header('Content-Type: application/json');
			require 'App/refresh.php';

		// Si l'url est "signout" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "signout" && empty($request['args'][1])){

			header('Content-Type: application/json');
			require 'App/logout.php';

		// Si l'url est "validate" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "validate" && empty($request['args'][1])){

			header('Content-Type: application/json');
			require 'App/validate.php';

		// Si l'url est "invalidate" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "invalidate" && empty($request['args'][1])){

			header('Content-Type: application/json');
			require 'App/invalidate.php';

		// Si l'url est "register" et qu'il n'y a rien d'autre derriere
		}elseif($request['args'][0] == "register" && empty($request['args'][1])){

			// Si la page est activé dans la config
			// Sinon, erreur 404
			if(Core\Config::get('activeRegisterPage')){
				require 'App/register.php';
			}else{
				header("HTTP/1.0 404 Not Found");
				error(1);
			}

		// Sinon, erreur 404
		}else{

			header("HTTP/1.0 404 Not Found");
			error(1);

		}
	}else{
		unlink("install.php");
		echo "<meta http-equiv='refresh' content='0'>";
	}
}else{
	require 'install.php';
}