<?php

namespace Parfumix\Imageonfly;

interface ImageProcessorInterface {

    /**
     * Upload images ..
     *
     * @param $images
     * @param $path
     * @param array $filters
     * @return mixed
     */
    public function upload($images, $path, array $filters = array());
}