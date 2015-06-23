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

/**
 * The Database Class - This class is used to load the database from the configuration
 * and then store it, to get it later
 *
 * @package Core
 * @author Vavaballz & TheShark34
 * @version 1.0.0-SNAPSHOT
 */
Class Database{

	/**
	 * The current database config
	 */
	protected static $config;

	/**
	 * The current database
	 */
	protected static $db;

    /**
     * Return an instance of the database as a \PDO Object instance
     *
     * @return Current database instance (\PDO)
     */
	public static function getInstance() {
		// If the current database and the current config are null
		if(is_null(self::$db) && is_null(self::$config)) {
			// Getting the database config from the config file
			self::$config = Config::get('database');

			// Trying to create a \PDO object from the configuration
			try {
				$db = new \PDO('mysql:dbname='. self::$config['database'] .';host='. self::$config['host'] .'', self::$config['username'], self::$config['password']);
			} catch (PDOException $e) {
				// If it failed, printing the error message
			    echo $e->getMessage();
			}

			// Setting the current database
			self::$db = $db;
		}

		// Returning the current database
		return self::$db;
	}

}