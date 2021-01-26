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

// If the username, the password, and the validation password POST variables exist
if (isset($_POST["username"]) || isset($_POST["password"]) || isset($_POST["vpassword"])) {
    // Getting the username, the password, and the validation password
    $username = $_POST["username"];
    $password = $_POST["password"];
    $vpassword = $_POST["vpassword"];

    // If no one is empty
    if(!empty($username) && !empty($password) && !empty($vpassword)) {
        // Sending a request to the database to get a user with the same name as the given name
        $req = Core\Queries::execute('SELECT * FROM openauth_users WHERE username=:username', ['username' => $username]);

        // If the request is null, or is empty (so the user doesn't already exist)
        if(is_null($req) || empty($req))
            // If the password and the validation password are the same
            if ($password == $vpassword) {
                // Generating a new GUID
                $guid = getGUID();

                // Generating a new UUID
                $uuid = md5(uniqid(rand(), true));

                // Hashing the given password
                $password = hash('sha256', $password);

                $accessToken = md5(uniqid(rand(), true));
                $clientToken = getClientToken();

                Core\Queries::execute(
                    'INSERT INTO openauth_users (guid, uuid, username, password, accessToken, clientToken) VALUES (:guid, :uuid, :username, :password, :accessToken, :clientToken)', [
                        'username' => $username,
                        'uuid' => $uuid,
                        'password' => $password,
                        'guid' => $guid,
                        'accessToken' => $accessToken,
                        'clientToken' => $clientToken]
                );
                
                // Setting the 'You are now suscribed' message
                $notif = "Vous êtes bien inscrits !";
            }

            // Else if the password and the validation password aren't the same
            else
                // Setting the 'Different passwords' message
                $notif = 'Les mots de passe sont different !';

        // Else if the request sent something (so a user with this name already exists)
        else
            // Setting the 'User already exists' message
            $notif = 'Le pseudo est déjà utilise !';
    }

    // Else if one of the fields is empty
    else
        // Setting the 'One of the fields is missing' message
        $notif = 'Un ou plusieurs champs sont manquant !';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OpenAuth Server - Inscription</title>

        <!-- The OpenAuth Icon -->
        <link rel="icon" href="http://theshark34.github.io/OpenAuth-Server/icon.png" />

        <!-- Bootstrap -->
        <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link href="http://theshark34.github.io/OpenAuth-Server/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="fulldiv classic">
            <img src="http://theshark34.github.io/OpenAuth-Server/logo.png" />

            <h1><b><u>Inscription</u></b></h1>
            <br />
            <p class="marged-paragraph">
            <!-- Printing a message if needed -->
            <?= isset($notif) ? $notif : "" ?>
              <form method="post" action="">
                <div id="first-part">
                  <label for="username">Pseudo</label> : <input class="text-field" type="text" name="username" id="username" required/>
                  <br />
                  <label for="password">Mot de Passe</label> : <input class="text-field" type="password" name="password" id="password" required/>
                  <br />
                  <label for="password">Vérification du mot de passe</label> : <input class="text-field" type="password" name="vpassword" id="password" required/>
                  <br /><br />

                  <input class="submit-button" type="submit" value="S'inscrire" />
                </div>
              </form>
            </p>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </body>
</html>
