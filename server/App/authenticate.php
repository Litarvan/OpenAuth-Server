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
 * Verify if a user exist and
 * if he had right credentials
 *
 * @param $username
 * @param $password
 * @return bool
 */
function auth($username, $password){
	$req = Core\Queries::execute("SELECT * FROM openauth_users WHERE username = :username", ['username' => $username]);
    // Si la requete trouve un utilisateur
    // Sinon, return false
	if(isset($req) && !empty($req)){
		$password = hash('sha256', $password);
        // Si le mot de passe est le bon : return true
        // Sinon, return false
		if($password == $req->password){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

/**
 * Send a response with the agent
 *
 * @param $username
 * @param $clientToken
 * @param $agentName
 * @param $agentVersion
 */
function send_response_agent($username, $clientToken, $agentName, $agentVersion){	
	$accessToken = md5(uniqid(rand(), true));
	$req = Core\Queries::execute("SELECT * FROM openauth_users WHERE username = :username", ['username' => $username]);
	$playerUUID = $req->UUID;
    // Si le $clientToken est vide alors ca le genere et l'enregistre
    // Sinon, il l'enregistre directement
	if(empty($clientToken)){
		$newClientToken = getClientToken(32);
		Core\Queries::execute(
			'UPDATE openauth_users SET accessToken=:accessToken, clientToken=:clientToken WHERE username=:username', 
			[
				'accessToken' => $accessToken,
				'clientToken' => $newClientToken,
				'username' 	  => $username,
			]);
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
				[
					'id' => $playerUUID,
					'name' => $username
				]
			]
		];
		$result = json_encode($result);
		echo $result;
	}else{
		Core\Queries::execute(
			'UPDATE openauth_users SET accessToken=:accessToken WHERE username=:username',
			[
				'accessToken' => $accessToken,
				'username'	  => $username
			]
			);
		$result = [
			'accessToken' => $accessToken,
			'clientToken' => $clientToken,
			'availableProfiles' => [ 
				[
					'id' => $playerUUID,
					'name' => $username
				]
			],
			'selectedProfile' => [
				[
					'id' => $playerUUID,
					'name' => $username
				]
			]
		];
		$result = json_encode($result);
		echo $result;
	}
}

/**
 * Return the response
 * without the agents
 *
 * @param $username
 * @param $clientToken
 */
function send_response($username, $clientToken){
	
	$accessToken = md5(uniqid(rand(), true));
	// Si $clientToken est vide alors il le genere et l'enregistre
    // Sinon, il l'enregistre directement
	if(empty($clientToken)){
		$newClientToken = getClientToken();
		Core\Queries::execute(
			"UPDATE members SET accessToken=:accessToken, clientToken=:clientToken WHERE username=:username",
			[
				'accessToken' => $accessToken,
				'clientToken' => $newClientToken,
				'username'	  => $username
			]
		);
		$response = array(
			'accessToken' => $accessToken,
			'clientToken' => $newClientToken
		);
		$result = json_encode($response);
		echo $result;
	}else{
		Core\Queries::execute(
			"UPDATE members SET accessToken=:accessTokenWHERE username=:username",
			[
				'accessToken' => $accessToken,
				'username'	  => $username
			]
		);
		$response = array(
			'accessToken' => $accessToken,
			'clientToken' => $clientToken
		);
		$result = json_encode($response);
		echo $result;
	}
}

// Si la method est bien POST et que le content-type est en json
// Sinon, return l'erreur d'id 1
if($request['method'] == "POST"){
	if($request['content-type'] == "application/json"){
        // On récupere le contenu envoyé
		$input = file_get_contents("php://input");

		$getContents = json_decode($input, true);

		$username = isset($getContents['username']) ? $getContents['username'] : null;
		$password = isset($getContents['password']) ? $getContents['username'] : null;
		$clientToken = isset($getContents['clientToken']) ? $getContents['clientToken'] : null;
		$agent = isset($getContents['agent']) ? $getContents['agent'] : null;

        // Si il se connecte alors on continue
        // Sinon, return l'erreur d'id 3
		if(auth($username, $password)){
            // Si $agent n'est pas null alors il fait send_response_agent
            // Sinon, il fait send_response
			if(!is_null($agent)){
				send_response_agent($username, $clientToken, $agent['name'], $agent['version']);
			}else{
				send_response($username, $clientToken);
			}
		}else{
			echo error(3);
		}
	}else{
		echo error(6);
	}
}else{
	echo error(1);
}