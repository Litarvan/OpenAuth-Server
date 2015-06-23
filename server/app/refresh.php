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

// If the request method is POST
if($request['method'] == "POST")
	// If the content-type is JSON
	if($request['content-type'] == "application/json") {
		// Getting the input JSON
		$input = file_get_contents("php://input");

		// Decoding the JSON
		$getContents = json_decode($input, true);

		// Getting the given client token
		$clientToken = !empty($getContents['clientToken']) ? $getContents['clientToken'] : null;

		// Getting the given access token
		$accessToken = !empty($getContents['accessToken']) ? $getContents['accessToken'] : null;

		// Sending a request to the database to get the user from the access token
		$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE accessToken=:accessToken', ['accessToken' => $accessToken]);

		// If the user was found (the request response isn't empty)
		if(!empty($req))
			// If the given client token is the same as the one of the database
			if($req->clientToken == $clientToken) {
				// Generating a new access token
				$newAccessToken = md5(uniqid(rand(), true));

				// Sending a request to the database to update the access token of the user
				Core\Queries::execute('UPDATE openauth_users SET accessToken=:accessToken WHERE clientToken=:clientToken', ['accessToken' => $newAccessToken, 'clientToken' => $clientToken]);
				
				// Creating an array of the new infos
				$jsonArray = array(
						'accessToken' => $newAccessToken,
						'clientToken' => $clientToken
				);

				// Printing it as a JSON
				echo json_encode($jsonArray);
			}

			// Else if the given client token isn't the same as the one of the database
			else
				// Printing the third error
				echo error(3);
			

		// Else if the user wasn't found (the request response is empty)
		else
			// Printing the fourth error
			echo error(4);
		
	}

	// Else if the content-type isn't JSON
	else
		// Printing the sixth error
		echo error(6);
	
// Else if the request method isn't POST
else
	// Printing the first error
	echo error(1);