<?php

namespace Parfumix\Imageonfly;

/**
 * Get root path
 *
 * @param null $directory
 * @return string
 */
function get_path($directory = null) {
    $currentPath = realpath(dirname(__FILE__));

    $rootPath = join(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $currentPath), 0, -3));

    if ($directory)
        $rootPath .= DIRECTORY_SEPARATOR . $directory;

    return $rootPath;
}

/**
 *
 * Get public path ..
 * @param null $directory
 * @return string
 */
function publicPath($directory = null) {
    return get_path(
        'public' . DIRECTORY_SEPARATOR . $directory
    );
}

/**
 * Get config path ..
 * @param null $directory
 * @return string
 */
function configPath($directory = null) {
    return get_path(
        'config' . DIRECTORY_SEPARATOR . $directory
    );
}

/**
 * Return first value of array .
 *
 * @param array $array
 */
function arrayFirst(array $array) {

    if(! count($array))
        return;

    return $array[0];
}