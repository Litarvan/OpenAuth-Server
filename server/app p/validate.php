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

		// Decoding it
		$getContents = json_decode($input, true);

		// Getting the access token from it
		$accessToken = !empty($getContents['accessToken']) ? $getContents['accessToken'] : null;

		// If the given access token isn't null
		if(!is_null($accessToken)){
			// Sending a request to the database to get the user from the given access token
			$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE accessToken=:accessToken', ['accessToken' => $accessToken]);

			// If the request response is empty
			if(empty($req))
				// Printing the fourth error
				echo error(4);
		}

		// Else if the given access token is null
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
