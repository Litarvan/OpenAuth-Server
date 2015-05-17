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

Class Config{

	protected static $config;

	protected static function getConfig(){
		if(is_null(self::$config)){
			self::$config = require 'config.php';
		}
		return self::$config;
	}

	public static function get($values){
		$config = self::getConfig();
		$values = explode('.', $values);
		$val = $config;

		foreach($values as $v){
			$val = $val[$v];
		}
		return $val;
	}

}