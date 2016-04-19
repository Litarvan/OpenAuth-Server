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

use PDOException;
use Slim\Http\Request;
use Slim\Http\Response;

class InstallController extends Controller
{

    public function install(Request $request, Response $response)
    {
        if (isset($this->ci->get("settings")['db']) && !empty($this->ci->get("settings")['db']))
            return error(1, $response);
        return $this->ci->get("view")->render($response, 'install/install.twig');
    }

    public function install_post(Request $request, Response $response)
    {
        if (isset($this->ci->get("settings")['db']) && !empty($this->ci->get("settings")['db']))
            return error(1, $response);
        $params = $request->getParams();
        $notif = [];
        if (!empty($_POST['host']) && !empty($_POST['username']) && !empty($_POST['dbname']) && !empty($_POST['ownername'])) {
            try {
                $pdo = new \PDO('mysql:dbname=' . $params['dbname'] . ';host=' . $params['host'] . '', $params['username'], $params['password']);
                // Checking if the database exists
                $exist = $pdo->query("SHOW TABLES LIKE 'openauth_users'");
                if ($exist->rowCount() == 0) {
                    $sql = file_get_contents(root_path("config/db.sql"));
                    $req = $pdo->prepare($sql);
                    $req->execute();
                }
                // Getting the base config file
                $config_file = file_get_contents(root_path('config/settings.example.php'));
                $config_file = str_replace("'localhost'", "'" . $params['host'] . "'", $config_file);
                $config_file = str_replace("'dbname'", "'" . $params['dbname'] . "'", $config_file);
                $config_file = str_replace("'name'", "'" . $params['username'] . "'", $config_file);
                $config_file = str_replace("'pass'", "'" . $params['password'] . "'", $config_file);
                $config_file = str_replace("'ownername'", "'" . $params['ownername'] . "'", $config_file);
                $config_file = str_replace("'prikey'", "'" . md5(uniqid(rand(), true)) . "'", $config_file);
                file_put_contents(root_path('config/settings.php'), $config_file);
                return $response->withHeader('Location', $this->ci->get('router')->pathFor('home'));
            } catch (PDOException $e) {
                $notif = ['type' => 'danger', 'msg' => 'Impossible de se connecter à la base de données !'];
            }
        } else
            $notif = ['type' => 'danger', 'msg' => 'Vous devez remplir tous les champs !'];
        return $this->ci->get("view")->render($response, 'install/install.twig', compact("notif"));
    }

}