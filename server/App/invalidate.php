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

// Si la method est bien POST et que le content-type est en json
// Sinon, return l'erreur d'id 1
if($request['method'] == "POST"){
	if($request['content-type'] == "application/json"){
        // On récupere le JSON
		$input = file_get_contents("php://input");
		$getContents = json_decode($input, true);
		$accessToken = !empty($getContents['accessToken']) ? $getContents['accessToken'] : null;
		$clientToken = !empty($getContents['clientToken']) ? $getContents['clientToken'] : null;
        // Si ils ne sont pas null
		if(!is_null($accessToken) && !is_null($clientToken)){
			$req = Core\Queries::execute('SELECT * FROM openauth_users WHERE clientToken=:clientToken', ['clientToken' => $clientToken]);
            // Si le clientToken existe en base de donnée
			if(!empty($req)){
                // Si les deux accessToken sont égaux
                // SInon, erreur d'id 4
				if($accessToken == $req->accessToken){
					Core\Queries::execute("UPDATE openauth_users SET accessToken=:accessToken WHERE clientToken=:clientToken", ['clientToken' => $clientToken, 'accessToken' => '']);
				}else{
					echo error(4);
				}
			}else{
				echo error(4);
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