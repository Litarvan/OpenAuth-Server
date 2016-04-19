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
namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class AppController extends Controller
{

    public function home(Request $request, Response $response)
    {
        if(!(isset($this->ci->get("settings")['db']) && !empty($this->ci->get("settings")['db'])))
            return $response->withHeader('Location', $this->ci->get('router')->pathFor('install'));
        $data = array(
            'Status' => 'OK',
            'Runtime-Mode' => 'productionMode',
            'Application-Author' => 'Litarvan & Vavaballz',
            'Application-Description' => 'OpenAuth Server.',
            'Specification-Version' => '2.0.0-SNAPSHOT',
            'Application-Name' => 'openauth.server',
            'Implementation-Version' => '2.0.0_build01',
            'Application-Owner' => $this->ci->get('settings')['owner'],
        );
        return $response->withJson($data);
    }

}
