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
 * The Config class
 *
 * This class is used to load a value from the config (config.php file)
 *
 * @package Core
 * @author Vavaballz & TheShark34
 * @version 1.0.0-SNAPSHOT
 */
Class Config {

	/**
	 * The config file
	 */
	protected static $config;

    /**
     * Return an instance of the config
     *
     * @return The config instance
     */
	protected static function getConfig() {
		// If the current config is null
		if(is_null(self::$config))
			// Loading it
			self::$config = require 'config.php';

		// Returning the loaded config
		return self::$config;
	}

    /**
     * Get the value $values in the current config (config.php file)
     * 
     * @param $values
     *            The values to get
     * @return $value['foo']['bar']
     */
	public static function get($values) {
		$config = self::getConfig();
		$values = explode('.', $values);
		$val = $config;

		foreach($values as $v)
			$val = $val[$v];

		return $val;
	}

}