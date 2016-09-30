<?php
/*
* Copyright 2015 Vavaballz
*
* This file is part of OpenAuth-Server V2.
* OpenAuth-Server V2 is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* OpenAuth-Server V2 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public License
* along with OpenAuth-Server V2.  If not, see <http://www.gnu.org/licenses/>.
*/
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Auto throw errors
 *
 * @param integer $error
 * @param \Slim\Http\Response $response
 * @return array|string
 */
function error($error, Response $response)
{
    $errors = [
        [
            "error" => "Method Not Allowed",
            "errorMessage" => "The method specified in the request is not allowed for the resource identified by the request URI"
        ],
        [
            "error" => "Not Found",
            "errorMessage" => "The server has not found anything matching the request URI"
        ],
        [
            "error" => "ForbiddenOperationException",
            "errorMessage" => "Invalid credentials. Invalid username or password."
        ],
        [
            "error" => "ForbiddenOperationException",
            "errorMessage" => "Invalid token."
        ],
        [
            "error" => "IllegalArgumentException",
            "errorMessage" => "Access token already has a profile assigned."
        ],
        [
            "error" => "Unsupported Media Type",
            "errorMessage" => "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method"
        ]
    ];

    // Creating an array by the error and the error message
    if (!array_key_exists($error, $errors))
        $response->withStatus(500)
            ->withJson(["error" => "Unknown error"]);

    $status = 500;
    if($error == 1)
        $status = 404;

    // Returning the JSON
    return $response->withStatus($status)->withJson($errors[$error]);
}

/**
 * Crypt a password with BCRYPT algo
 *
 * @param string $password
 * @return bool|string
 */
function bcrypt($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Generate a random UUID
 *
 * @return string
 */
function generateUUID()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * @param Request $request
 * @return bool
 */
function isJsonRequest(Request $request)
{
    return (current($request->getHeader("Content-Type")) == "application/json");
}

/**
 * @param Request $request
 * @param Response $response
 * @return array|string
 */
function onlyJsonRequest(Request $request, Response $response)
{
    if (!isJsonRequest($request))
        return \error(4, $response);
}

function root_path($path=''){
    return ROOT_PATH . DIRECTORY_SEPARATOR . $path;
}
