<?php

namespace Parfumix\Imageonfly;

use Parfumix\Imageonfly\Templates\Thumbnail;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageProcessor {

    public $templates = [
        'thumbnail' => Thumbnail::class
    ];

    /**
     * Upload array of images ..
     *
     * @param $images
     * @param $path
     * @param array $filters
     * @return array
     */
    public static function upload($images, $path, $filters = []) {
        if (!is_array($images))
            $images = (array)$images;

        return array_map(function ($image) use ($path, $filters) {
            if (! $image instanceof UploadedFile)
                $image = new UploadedFile($image, null);

            $imager = \Image::make($image);

            foreach ($filters as $filter)
                $imager = (new $filter)->applyFilter($imager);

            return $imager->save(
                sprintf('%s%s.%s', $path, uniqid(), $image->guessClientExtension())
            );

        }, array_filter($images));
    }

}