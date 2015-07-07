<?php

/**
 * Get path from root ..
 */
if(! function_exists('get_path')) {

    function get_path($directory = null) {
        $currentPath = realpath(dirname(__FILE__));

        $rootPath = join(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $currentPath), 0, -3));

        if ($directory)
            $rootPath .= DIRECTORY_SEPARATOR . $directory;

        return $rootPath;
    }
}

/**
 *
 * Get public path ..
 *
 */
if(! function_exists('publicPath')) {

    function publicPath($directory = null) {
        return get_path(
            'public'. DIRECTORY_SEPARATOR . $directory
        );
    }
}

/**
 * Get config path ..
 *
 */
if(! function_exists('configPath')) {

    function configPath($directory = null) {
        return get_path(
            'config'. DIRECTORY_SEPARATOR . $directory
        );
    }
}