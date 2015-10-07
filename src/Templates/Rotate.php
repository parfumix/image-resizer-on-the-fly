<?php

namespace Parfumix\Imageonfly\Templates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Rotate implements FilterInterface {

    /**
     * @var
     */
    private $rotate;

    public function __construct($options) {
        $this->rotate = $options['rotate'];
    }

    /**
     * Applies filter to given image
     *
     * @param  Image $image
     * @return Image
     */
    public function applyFilter(Image $image) {
        return $image->rotate($this->rotate);
    }
}