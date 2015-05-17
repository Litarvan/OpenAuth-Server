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
		$username = !empty($getContents['username']) ? $getContents['username'] : null;
		$password = !empty($getContents['password']) ? $getContents['password'] : null;
		if(!is_null($username) & !is_null($password)){
			$req = Core\Queries::execute('SELECT * FROM users WHERE username=:username', ['username' => $username]);
			if(!empty($req)){
				$password = hash('sha256', $password);
				if($password == $req->password){
					Core\Queries::execute('UPDATE users SET accessToken=:accessToken WHERE username=:username', ['username' => $username, 'accessToken' => null]);
					echo null;
				}else{
					echo error(3);
				}
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