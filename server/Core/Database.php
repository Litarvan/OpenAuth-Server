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
 * Class Database
 * @package Core
 * @author Vavaballz
 * @version 1.0.0
 */
Class Database{

	protected static $config;
	protected static $db;

    /**
     * Return an instance of PDO
     * @return \PDO
     */
	public static function getInstance(){
		if(is_null(self::$db) && is_null(self::$config)){
			self::$config = Config::get('database');
			try{
				$db = new \PDO('mysql:dbname='. self::$config['database'] .';host='. self::$config['host'] .'', self::$config['username'], self::$config['password']);
			} catch (PDOException $e) {
			    echo $e->getMessage();
			}
			self::$db = $db;
		}
		return self::$db;
	}

}