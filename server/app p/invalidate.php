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
	if($request['content-type'] == "application/json"){
        // Getting the input JSON
		$input = file_get_contents("php://input");

		// Decoding the JSON
		$getContents = json_decode($input, true);

		// Getting the access token from the JSON
		$accessToken = !empty($getContents['accessToken']) ? $getContents['accessToken'] : null;

		// Getting the client token from the JSON
		$clientToken = !empty($getContents['clientToken']) ? $getContents['clientToken'] : null;

        // If they aren't null
		if(!is_null($accessToken) && !is_null($clientToken)) {
			// Sending a request to the database to get the user from the client token
			$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE clientToken=:clientToken', ['clientToken' => $clientToken]);

            // If the client token exists in the database (so the response isn't empty)
			if(!empty($req))
                // If the given access token and the database access token are the same
				if($accessToken == $req->accessToken)
					// Updating the access and the client token in the database
					Core\Queries::execute("UPDATE openauth_users SET accessToken=:accessToken WHERE clientToken=:clientToken", ['clientToken' => $clientToken, 'accessToken' => '']);
				
				// Else if they aren't the same
				else
					// Returning the fourth error
					echo error(4);

			// Else if the client token doesn't exist (the reponse is empty)
			else
				// Returning the fourth error
				echo error(4);
		} 

		// Else if one of them is null
		else
			echo error(4);
	}

	// Else if the content-type isn't JSON
	else
		// Returning the sixth error
		echo error(6);

// Else if the request method isn't POST
else
	// Returning the first error
	echo error(1);