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

function auth($username, $password){
	$req = Core\Queries::execute("SELECT * FROM users WHERE username = :username", ['username' => $username]);
	if(isset($req) && !empty($req)){
		$password = hash('sha256', $password);
		if($password == $req->password){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function send_response_agent($username, $clientToken, $agentName, $agentVersion){	
	$accessToken = md5(uniqid(rand(), true));
	$req = Core\Queries::execute("SELECT * FROM users WHERE username = :username", ['username' => $username]);
	$playerUUID = $req->UUID;
	if(empty($clientToken)){
		$newClientToken = getClientToken(32);
		Core\Queries::execute(
			'UPDATE users SET accessToken=:accessToken, clientToken=:clientToken WHERE username=:username', 
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
			'UPDATE users SET accessToken=:accessToken WHERE username=:username',
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

function send_response($username, $clientToken){
	
	$accessToken = md5(uniqid(rand(), true));
	
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

if($request['method'] == "POST"){
	if($request['content-type'] == "application/json"){
		$input = file_get_contents("php://input");

		$getContents = json_decode($input, true);

		$username = isset($getContents['username']) ? $getContents['username'] : null;
		$password = isset($getContents['password']) ? $getContents['username'] : null;
		$clientToken = isset($getContents['clientToken']) ? $getContents['clientToken'] : null;
		$agent = isset($getContents['agent']) ? $getContents['agent'] : null;
		if(auth($username, $password)){
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