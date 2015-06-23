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

// Importing the Database class
use Core\Database;

/**
 * The Queries class - This class is used to execute some queries with the database
 *
 * @package Core
 * @author Vavaballz & TheShark34
 * @version 1.0.0-SNAPSHOT
 */
Class Queries{

    /**
     * Execute a PDO query
     * 
     * @param $query
     *            The query to execute
     * @return A array with the results
     */
	public static function query($query){
		// Executing the query from the database instance
		$results = Database::getInstance()->query($query);

		// Fetching the results
		$results = $results->fetchAll(\PDO::FETCH_OBJ);

		// Returning the fetched results
		return $results;
	}

    /**
     * Execute a PDO prepare with execute
     * 
     * @param $query
     *            The query to execute
     * @param array $params
     *            The query parameters
     * @return An array with the results
     */
	public static function execute($query, $params=[]){
		// Preparing the query with the database instance
		$results = Database::getInstance()->prepare($query);

		// Executing the query with the given parameters
		$results->execute($params);

		// Fetching the results
		$results = $results->fetchAll(\PDO::FETCH_OBJ);

		// Checking them
		if(count($results) < 2)
			$results = current($results);

		// Returning them
		return $results;
	}

}