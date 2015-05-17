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

use Core\Queries;
use Core\Config;

function preint_r($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
function error($errortype){	
	// The errors types can be found here : http://wiki.vg/Authentication

	switch($errortype){
		case 1:
			$error = "Method Not Allowed";
			$cause = "";
			$errorMessage = "The method specified in the request is not allowed for the resource identified by the request URI";
			$notes = "Something other than a POST request was received.";
			break;
		case 2:
			$error = "Not Found";
			$cause = "";
			$errorMessage = "The server has not found anything matching the request URI";
			$notes = "Non-existing endpoint was called.";
			break;
		case 3:
			$error = "ForbiddenOperationException";
			$cause = "";
			$errorMessage = "Invalid credentials. Invalid username or password.";
			$notes = "";
			break;
		case 4:
			$error = "ForbiddenOperationException";
			$cause = "";
			$errorMessage = "Invalid token.";
			$notes = "";
			break;
		case 5:
			$error = "IllegalArgumentException";
			$cause = "";
			$errorMessage = "Access token already has a profile assigned.";
			$notes = "Selecting profiles isn't implemented yet.";
			break;
		case 6:
			$error = "Unsupported Media Type";
			$cause = "";
			$errorMessage = "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method";
			$notes = "Data was not submitted as application/json";
			break;
		default:
			echo "Unknown error type";
			return;

	}
	$errors = array(
		'error' => $error,
		'errorMessage' => $errorMessage,
	);

	$errors = json_encode($errors);

	echo $errors;
	return;
}

function getGUID(){
    if (function_exists('com_create_guid')){
        $guid = com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $guid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
    }
    return strtolower(str_replace(['{', '}'], '', $guid));
}

function getClientToken() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), 
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), 
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff )
	);
}
