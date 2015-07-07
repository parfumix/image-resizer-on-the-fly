<?php

namespace Parfumix\Imageonfly\Templates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Thumbnail implements FilterInterface {

    /**
     * Applies filter to given image
     *
     * @param  Image $image
     * @return Image
     */
    public function applyFilter(Image $image) {
        return $image->fit(250);
    }
}