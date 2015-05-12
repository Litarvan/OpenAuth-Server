<!--
   Copyright 2015 TheShark34

   This file is part of OpenAuth.

   OpenAuth is free software: you can redistribute it and/or modify
   it under the terms of the GNU Lesser General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   OpenAuth is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Lesser General Public License for more details.

   You should have received a copy of the GNU Lesser General Public License
   along with OpenAuth.  If not, see <http://www.gnu.org/licenses/>.
-->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OpenAuth Server - Configuration</title>

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

            <h1><b><u>Configuration</u></b></h1>
            <br />
            <p class="marged-paragraph">
              <div id="welcome-message" style="font-size: 20px">
                Bienvenue dans la configuration de votre serveur OpenAuth. Si vous voyez ce message, c'est que l'installation s'est bien passée. Super !
              </div>

              <br />

              <form method="post" action="admin/config.php">
                <h3><b>Configuration de l'access admin</b></h3>

                <div id="first-part">
                  <br/>
                  Nous allons d'abord configurer la protection du dossier admin/ qui contient tout ce qui permet l'administration du serveur :

                  <br /><br /><br />

                  <label for="username">Pseudo</label> : <input class="text-field" type="text" name="username" id="username" required/>
                  <br />
                  <label for="password">Mot de Passe</label> : <input class="text-field" type="password" name="password" id="password" required/>
                  <br /><br />
                </div>

                <h3><b>Configuration de la BDD</b></h3>

                <div id="second-part">
                  Donc maintenant, passons à la configuration de la base de donnée.
                  <br />
                  Merci d'entrer le pseudo et le mot de passe du serveur SQL, et le nom de la BDD que vous voulez créer, <b>mais si elle existe déjà elle sera supprimée !</b>
                  <br /><br /><br />

                  <label for="bdd-username">Pseudo du serveur</label> : <input class="text-field" type="text" name="bdd-username" id="bdd-username" required/>
                  <br />
                  <label for="bdd-password">Mot de Passe du serveur</label> : <input class="text-field" type="password" name="bdd-password" id="bdd-password" required/>
                  <br />
                  <label for="bdd-name">Nom de la BDD</label> : <input class="text-field" type="text" name="bdd-name" id="bdd-name" required/>
                  <br /><br />

                  <input class="submit-button" type="submit" value="Appliquer" />
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
