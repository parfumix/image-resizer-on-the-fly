<?php

namespace Parfumix\Imageonfly\Templates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Crop implements FilterInterface {

    /**
     * @var
     */
    private $x;

    /**
     * @var
     */
    private $y;

    /**
     * @var
     */
    private $with;

    /**
     * @var
     */
    private $height;

    public function __construct($options) {
        $this->x = $options['x'];
        $this->y = $options['y'];
        $this->with = $options['width'];
        $this->height = $options['height'];
    }

    /**
     * Applies filter to given image
     *
     * @param  Image $image
     * @return Image
     */
    public function applyFilter(Image $image) {
        return $image->crop((int)$this->with, (int)$this->height, (int)$this->x, (int)$this->y);
    }
}