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

/**
 * Debug function like var_dump or dd() in Laravel
 * @param $var
 */
function preint_r($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
 * Return the error
 * @param $errortype
 */
function error($errortype){	
	// The errors types can be found here : http://wiki.vg/Authentication

	switch($errortype){
		case 1:
			$error = "Method Not Allowed";
			$errorMessage = "The method specified in the request is not allowed for the resource identified by the request URI";
			break;
		case 2:
			$error = "Not Found";
			$errorMessage = "The server has not found anything matching the request URI";
			break;
		case 3:
			$error = "ForbiddenOperationException";
			$errorMessage = "Invalid credentials. Invalid username or password.";
			break;
		case 4:
			$error = "ForbiddenOperationException";
			$errorMessage = "Invalid token.";
			break;
		case 5:
			$error = "IllegalArgumentException";
			$errorMessage = "Access token already has a profile assigned.";
			break;
		case 6:
			$error = "Unsupported Media Type";
			$errorMessage = "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method";
			break;
		default:
			return "Unknown error type";

	}
	$errors = array(
		'error' => $error,
		'errorMessage' => $errorMessage,
	);

	$errors = json_encode($errors);


	return $errors;
}

/**
 * Generate a GUID
 * @return string
 */
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

/**
 * Generate a ClientToken
 * @return string
 */
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
