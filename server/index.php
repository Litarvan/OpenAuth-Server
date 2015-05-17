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

// if(is_null($request['content-type'])){
// 	error(6);
// 	return;
// }
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

}elseif($request['args'][0] == "authenticate" && empty($request['args'][1])){

	header('Content-Type: application/json');
	require 'App/authenticate.php';

}elseif($request['args'][0] == "refresh" && empty($request['args'][1])){

	header('Content-Type: application/json');
	require 'App/refresh.php';

}elseif($request['args'][0] == "signout" && empty($request['args'][1])){

	header('Content-Type: application/json');
	require 'App/logout.php';

}elseif($request['args'][0] == "validate" && empty($request['args'][1])){

	header('Content-Type: application/json');
	require 'App/validate.php';

}elseif($request['args'][0] == "invalidate" && empty($request['args'][1])){

	header('Content-Type: application/json');
	require 'App/invalidate.php';

}elseif($request['args'][0] == "register" && empty($request['args'][1])){

	require 'App/register.php';

}else{

	header("HTTP/1.0 404 Not Found");
	error(1);

}
