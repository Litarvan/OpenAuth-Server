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

if($request['method'] == "POST"){
	if($request['content-type'] == "application/json"){
		$input = file_get_contents("php://input");
		$getContents = json_decode($input, true);
		$clientToken = !empty($getContents['clientToken']) ? $getContents['clientToken'] : null;
		$accessToken = !empty($getContents['accessToken']) ? $getContents['accessToken'] : null;

		$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE accessToken=:accessToken', ['accessToken' => $accessToken]);
		if(!empty($req)){
			if($req->clientToken == $clientToken){
				$newAccessToken = md5(uniqid(rand(), true));
				Core\Queries::execute('UPDATE openauth_users SET accessToken=:accessToken WHERE clientToken=:clientToken', ['accessToken' => $newAccessToken, 'clientToken' => $clientToken]);
				$jsonArray = array(
						'accessToken' => $newAccessToken,
						'clientToken' => $clientToken
					);
				echo json_encode($jsonArray);
			}else{
				echo error(3);
			}
		}else{
			echo error(4);
		}
	}else{
		echo error(6);
	}
}else{
	echo error(1);
}