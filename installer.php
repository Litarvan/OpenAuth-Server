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
putenv('COMPOSER_HOME=' . __DIR__ . '/extracted/bin/composer');

function downloadServer()
{
    file_put_contents("server.zip", fopen("http://litarvan.github.io/OpenAuth-Server/server/openauth-server-2.0.0-SNAPSHOT.zip", "r"));
    $zip = new ZipArchive;
    $res = $zip->open('server.zip');
    if ($res === TRUE) {
        $zip->extractTo('./');
        $zip->close();
        echo 'woot!';
    } else {
        echo 'doh!';
    }
    unlink("server.zip");
}

function downloadComposer()
{
    file_put_contents("composer.phar", fopen('https://getcomposer.org/composer.phar', 'r'));
}

function extractComposer()
{
    if (file_exists('composer.phar')) {
        $composer = new Phar('composer.phar');
        $composer->extractTo('extracted');
    }
}

function command($command, $path)
{
    set_time_limit(-1);
    putenv('COMPOSER_HOME=' . __DIR__ . '/extracted/bin/composer');
    if (!file_exists($path)) {
        echo 'Invalid Path';
        die();
    }
    if (file_exists('extracted')) {
        require_once(__DIR__ . '/extracted/vendor/autoload.php');
        $input = new Symfony\Component\Console\Input\StringInput($command . ' -vvv -d ' . $path);
        $output = new Symfony\Component\Console\Output\StreamOutput(fopen('php://output', 'w'));
        $app = new Composer\Console\Application();
        $app->run($input, $output);
    }
}

if (empty($_GET['step']) && file_exists("composer.json")) {
    command("update", "./");
    header("Location: index.php");
}

if (empty($_GET['step']) && !file_exists("composer.json")) {
    downloadServer();
    header("Location: ?step=2");
} else if ($_GET['step'] == 2) {
    downloadComposer();
    header("Location: ?step=3");
} else if ($_GET['step'] == 3) {
    extractComposer();
    echo "<a href='installer.php'>Cliquez pour terminer l'installation !</a>";
}
