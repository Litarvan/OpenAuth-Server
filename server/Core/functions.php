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

// Importing the queries and the config classes
use Core\Queries;
use Core\Config;

/**
 * Print a message in <pre> balises (like var_dump or dd() in Laravel)
 *
 * @param $var
 *            The message to print
 */
function preint_r($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/**
 * Return the error from the given error type (from 1 to 6) as a JSON
 * The errors types can be found here : http://wiki.vg/Authentication
 *
 * It also return a 500 "Internal Server Error" response code
 *
 * @param $errortype
 *            The error type to get
 */
function error($errortype) {
	// Switching between the error types
	switch($errortype) {
		// First error, method not allowed (Malformed request)
		case 1:
			$error = "Method Not Allowed";
			$errorMessage = "The method specified in the request is not allowed for the resource identified by the request URI";
			break;

		// Second error, not found error (404 error)
		case 2:
			$error = "Not Found";
			$errorMessage = "The server has not found anything matching the request URI";
			break;

		// Third error, forbidden operation error (Invalid username or password)
		case 3:
			$error = "ForbiddenOperationException";
			$errorMessage = "Invalid credentials. Invalid username or password.";
			break;

		// Fourth error, forbidden operation error (Invalid token given)
		case 4:
			$error = "ForbiddenOperationException";
			$errorMessage = "Invalid token.";
			break;

		// Fifth error, illegal argument error (Access token already exists)
		case 5:
			$error = "IllegalArgumentException";
			$errorMessage = "Access token already has a profile assigned.";
			break;

		// Sixtsh error, unsuported media type (throwed when the content-type isn't JSON)
		case 6:
			$error = "Unsupported Media Type";
			$errorMessage = "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method";
			break;

		// Default error, if the type isn't between 1 and 6, Unkown error type
		default:
			return "Unknown error type";

	}

	// Creating an array by the error and the error message
	$errors = array(
		'error' => $error,
		'errorMessage' => $errorMessage,
	);

	// Encoding the array as a JSON
	$errors = json_encode($errors);

	// Setting the reponse code as 500 "Internal Server Error"
	http_response_code(500);

	// Returning the JSON
	return $errors;
}

/**
 * Generate a random GUID
 *
 * @return The generated GUID as a String
 */
function getGUID(){
	// If the com_create_guid PHP method exists
    if (function_exists('com_create_guid'))
    	// Using it to generate the GUID
        $guid = com_create_guid();

    // Else if the com_create_guid PHP method doesn't exist
    else
    	// Generating a random GUID
        mt_srand((double) microtime() * 10000); // Optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $guid = chr(123) // "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125); // "}"

    // Returning the generated uuid without the { and the }, and in lower cases
    return strtolower(str_replace(['{', '}'], '', $guid));
}

/**
 * Generate a random client token
 *
 * @return The generated client token as a String
 */
function getClientToken() {
	// Just generating a random client token and returning it
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
