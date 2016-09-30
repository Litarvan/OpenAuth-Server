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

use App\Model\User;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function register(Request $request, Response $response)
    {
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $username = isset($params['username']) ? $params['username'] : null;
        $password = isset($params['password']) ? $params['password'] : null;
        $vpassword = isset($params['verification_password']) ? $params['verification_password'] : null;
        $email = isset($params['email']) ? $params['email'] : null;
        $key = isset($params['key']) ? $params['key'] : null;

        if($key != $this->ci->get('settings')['private_key'])
            return $response->withStatus(500)->withJson(['error' => 'Wrong Key', 'errorMessage' => 'the private key is not defined or wrong']);

        if (!$username || !$password || !$vpassword || !$email)
            return $response->withStatus(500)->withJson(['error' => 'InvalidArgument', 'errorMessage' => 'username, password, verification_password or email have to be defined']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $response->withStatus(500)->withJson(['error' => 'Invalid Email', 'errorMessage' => 'The email field is not a valid email']);

        if (!User::where("username", $username)->orWhere("email", $email)->get()->isEmpty())
            return $response->withStatus(500)->withJson(['error' => 'Already Exists', 'errorMessage' => 'An account with this username or email already exists']);

        if ($password != $vpassword)
            return $response->withStatus(500)->withJson(['error' => 'Password Match', 'errorMessage' => 'The passwords does not match']);

        $user = new User();
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->UUID = generateUUID();
        $user->email = $email;
        $user->save();

        return $response->withJson([
            "username" => $user->username,
            "UUID" => $user->UUID,
            "email" => $user->email
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return array|Response|string
     */
    public function authenticate(Request $request, Response $response)
    {
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $email = isset($params['username']) ? $params['username'] : null;
        $password = isset($params['password']) ? $params['password'] : null;
        $clientToken = isset($params['clientToken']) ? $params['clientToken'] : null;
        //$agent = isset($params['agent']) ? $params['agent'] : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $response->withStatus(500)->withJson(['error' => 'Invalid Username', 'errorMessage' => 'The username field have to be an email']);

        $user = User::where("email", $email)->first();

        if (!$user)
            return error(2, $response);

        if (!password_verify($password, $user->password))
            return error(2, $response);

        $accessToken = md5(uniqid(rand(), true));
        if (is_null($clientToken))
            $clientToken = md5(uniqid(rand(), true));
        $user->accessToken = $accessToken;
        $user->clientToken = $clientToken;
        $user->save();

        return $response->withJson([
            'accessToken' => $accessToken,
            'clientToken' => $clientToken,
            'availableProfiles' => [
                [
                    'id' => $user->uuid,
                    'name' => $user->username
                ]
            ],
            'selectedProfile' => [
                'id' => $user->uuid,
                'name' => $user->username
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function refresh(Request $request, Response $response)
    {
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $clientToken = !empty($params['clientToken']) ? $params['clientToken'] : null;
        $accessToken = !empty($params['accessToken']) ? $params['accessToken'] : null;

        $user = User::where("accessToken", $accessToken)->first();


        if (!$user)
            return error(3, $response);

        if ($user->clientToken != $clientToken)
            return error(2, $response);

        $user->accessToken = md5(uniqid(rand(), true));
        $user->save();

        return $response->withJson([
            'accessToken' => $user->accessToken,
            'clientToken' => $clientToken
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function validate(Request $request, Response $response){
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $accessToken = !empty($params['accessToken']) ? $params['accessToken'] : null;

        if(is_null($accessToken))
            return error(3, $response);

        if(!User::where("accessToken", $accessToken)->first())
            return error(3, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function signout(Request $request, Response $response){
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $username = !empty($params['username']) ? $params['username'] : null;
        $password = !empty($params['password']) ? $params['password'] : null;

        if(!$username || !$password)
            return error(2, $response);

        if (!filter_var($username, FILTER_VALIDATE_EMAIL))
            return $response->withStatus(500)->withJson(['error' => 'Invalid Email', 'errorMessage' => 'The email field is not a valid email']);

        $user = User::where("email", $username)->first();

        if (!$user)
            return error(2, $response);

        if (!password_verify($password, $user->password))
            return error(2, $response);

        $user->accessToken = null;
        $user->save();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return array|Response|string
     */
    public function invalidate(Request $request, Response $response){
        onlyJsonRequest($request, $response);
        $params = $request->getParams();

        $accessToken = !empty($params['accessToken']) ? $params['accessToken'] : null;
        $clientToken = !empty($params['clientToken']) ? $params['clientToken'] : null;

        if($accessToken || $clientToken)
            return error(3, $response);

        $user = User::where("accessToken", $accessToken)->first();

        if(!$user)
            return error(3, $response);

        if ($clientToken != $user->clientToken)
            return error(3, $response);

        $user->accessToken = null;
        $user->save();
    }

}
