<?php
function delete($path)
{
    if (is_dir($path) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if (in_array($file->getBasename(), array('.', '..')) !== true) {
                if ($file->isDir() === true) {
                    rmdir($file->getPathName());
                } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                    unlink($file->getPathname());
                }
            }
        }

        return rmdir($path);
    } else if ((is_file($path) === true) || (is_link($path) === true)) {
        return unlink($path);
    }

    return false;
}

if (file_exists(root_path("composer.phar")))
    unlink(root_path("composer.phar"));
if (file_exists(root_path("extracted/")))
    delete(root_path("extracted/"));
if (file_exists(root_path("installer.php")))
    unlink(root_path("installer.php"));
unlink(root_path("bootstrap/after_install.php"));