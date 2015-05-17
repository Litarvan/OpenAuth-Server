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

namespace Core;

use Core\Database;

Class Queries{

	public static function query($query){
		$results = Database::getInstance()->query($query);
		$results = $results->fetchAll(\PDO::FETCH_OBJ);
		return $results;
	}

	public static function execute($query, $params=[]){
		$results = Database::getInstance()->prepare($query);
		$results->execute($params);
		$results = $results->fetchAll(\PDO::FETCH_OBJ);
		if(count($results) < 2){
			$results = current($results);
		}
		return $results;
	}

}