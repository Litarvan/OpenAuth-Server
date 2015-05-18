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

/**
 * Verify if a user exist and if he had right credentials
 *
 * @param $username
 *            The username of the user
 * @param $password
 *            The password of the user
 * @return bool
 *            True if yes, false if not
 */
function auth($username, $password) {
	// Sending the request to the database
	$req = Core\Queries::execute("SELECT * FROM openauth_users WHERE username = :username", ['username' => $username]);

	// If the request found a user
	if(isset($req) && !empty($req)) {
		// Hashing the given password
		$password = hash('sha256', $password);

		// If it is the same as the one of the database
		if($password == $req->password)
			// Returning true
			return true;

		// Else if the password aren't the same
		else
			// Returning false
			return false;

	}

	// Else if the request didn't find an user
	else
		// Returning false
		return false;
}

/**
 * Send a response with the agent
 *
 * @param $username
 *            The username of the user
 * @param $clientToken
 *            The client token
 * @param $agentName
 *            The name of the agent
 * @param $agentVersion
 *            The version of the agent
 */
function send_response_agent($username, $clientToken, $agentName, $agentVersion) {
	// Generating a random access token
	$accessToken = md5(uniqid(rand(), true));

	// Sending a request to the database to get the user
	$req = Core\Queries::execute("SELECT * FROM openauth_users WHERE username = :username", ['username' => $username]);

	// Getting the user UUID
	$playerUUID = $req->UUID;

	// If the given client token is empty
	if(empty($clientToken)) {
		// Generating a new client token
		$newClientToken = getClientToken(32);

		// Sending a request to the database to save the access token and the client token
		Core\Queries::execute(
	  		'UPDATE openauth_users SET accessToken=:accessToken, clientToken=:clientToken WHERE username=:username',
			[
				'accessToken' => $accessToken,
				'clientToken' => $newClientToken,
				'username' 	  => $username,
			]
		);

		// Creating an array of the result
		$result = [
			'accessToken' => $accessToken,
			'clientToken' => $newClientToken,
			'availableProfiles' => [
				[
					'id' => $playerUUID,
					'name' => $username
				]
			],
			'selectedProfile' => [
				'id' => $playerUUID,
				'name' => $username
			]
		];

		// Creating the JSON by the result array
		$result = json_encode($result);

		// Printing the JSON result
		echo $result;
	}

	// Else if the client token isn't empty
	else {
		// Sending a request to the database to save the access token
		Core\Queries::execute(
			'UPDATE openauth_users SET accessToken=:accessToken WHERE username=:username',
			[
				'accessToken' => $accessToken,
				'username' 	  => $username,
			]
		);

		// Creating an array of the result
		$result = [
			'accessToken' => $accessToken,
			'clientToken' => $newClientToken,
			'availableProfiles' => [
				[
					'id' => $playerUUID,
					'name' => $username
				]
			],
			'selectedProfile' => [
				'id' => $playerUUID,
				'name' => $username
			]
		];

		// Creating the JSON by the result array
		$result = json_encode($result);

		// Printing the JSON result
		echo $result;
	}
}

/**
 * Return the response without the agent
 *
 * @param $username
 *            The username of the user
 * @param $clientToken
 *            The client token
 */
function send_response($username, $clientToken){
	// Generating a random access token
	$accessToken = md5(uniqid(rand(), true));

	// If the client token is empty
	if(empty($clientToken)) {
		// Generating a new client token
		$newClientToken = getClientToken();

		// Sending a request to the database to save the new access and client tokens
		Core\Queries::execute(
			"UPDATE members SET accessToken=:accessToken, clientToken=:clientToken WHERE username=:username",
			[
				'accessToken' => $accessToken,
				'clientToken' => $newClientToken,
				'username'	  => $username
			]
		);

		// Creating a response array
		$response = array(
			'accessToken' => $accessToken,
			'clientToken' => $newClientToken
		);

		// Generating a JSON of the response
		$result = json_encode($response);

		// Printing it
		echo $result;
	}

	// Else if the client token isn't empty
	else {
		// Sending a request to the database to update the access token
		Core\Queries::execute(
			"UPDATE members SET accessToken=:accessToken WHERE username=:username",
			[
				'accessToken' => $accessToken,
				'username'	  => $username
			]
		);

		// Creating a response array
		$response = array(
			'accessToken' => $accessToken,
			'clientToken' => $clientToken
		);

		// Generating a JSON of it
		$result = json_encode($response);

		// Printing it
		echo $result;
	}
}

// If the request method is POST
if($request['method'] == "POST") {
	// If the content-type is JSON
	if($request['content-type'] == "application/json"){
		// Getting the sent content
		$input = file_get_contents("php://input");

		// Parsing the JSON
		$getContents = json_decode($input, true);

		// Getting the username, the password, the client token, and the agent
		$username = isset($getContents['username']) ? $getContents['username'] : null;
		$password = isset($getContents['password']) ? $getContents['username'] : null;
		$clientToken = isset($getContents['clientToken']) ? $getContents['clientToken'] : null;
		$agent = isset($getContents['agent']) ? $getContents['agent'] : null;

		// If the authentication worked
		if(auth($username, $password))
			// If the agent field isn't null
			if(!is_null($agent))
				// Sending a response with the agent
				send_response_agent($username, $clientToken, $agent['name'], $agent['version']);

			// Else if the agent field is null
			else
				// Sending a response without the agent
				send_response($username, $clientToken);
		else
			// Else returning the third error (see functions.php)
			echo error(3);
	}

	// Else if the content-type isn't JSON
	else
		// Returning the sixth error
		echo error(6);
}

// Else if the request method isn't POST
else
	// Returning the first error
	echo error(1);
