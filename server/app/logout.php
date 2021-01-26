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

		// Getting the given username
		$username = !empty($getContents['username']) ? $getContents['username'] : null;

		// Getting the given password
		$password = !empty($getContents['password']) ? $getContents['password'] : null;

		// If they aren't null
		if(!is_null($username) & !is_null($password)) {
			// Sending a request to the database to get the user from his username and his password
			$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE username=:username', ['username' => $username]);

			// If the user was found (the request response isn't empty)
			if(!empty($req)) {
				// Hashing the password
				$password = hash('sha256', $password);

				// If the password is the same as the one of the database
				if($password == $req->password)
					// Sending a request to the database to delete the user's access token
					Core\Queries::execute('UPDATE openauth_users SET accessToken=:accessToken WHERE username=:username', ['username' => $username, 'accessToken' => null]);

				// Else if the password aren't the same
				else
					// Returning the third error
					echo error(3);
			} else
				// Else if the request is empty (the user wasn't found)
				echo error(3);
		}

		// Else if one of them is null
		else
			// Returning the third error
			echo error(3);
		
	}

	// Else if the content-type isn't JSON
	else
		// Returning the sixth error
		echo error(6);
	
// Else if the request method isn't POST
else
	// Retruning the first error
	echo error(1);
