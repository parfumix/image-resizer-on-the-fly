<?php

namespace Parfumix\Imageonfly;

use Image as Imager;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class ImageProcessor implements ImageProcessorInterface {

    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration = array()) {

        $this->configuration = $configuration;
    }

    /**
     * Upload array of images ..
     *
     * @param $images
     * @param $path
     * @param array $filters
     * @return array
     */
    public function upload($images, $path, array $filters = []) {
        if (! is_array($images))
            $images = (array)$images;

        return array_map(function ($image) use ($path, $filters) {
            $image = Imager::make($image);

            foreach ($filters as $filter)
                $image = (new $filter)->applyFilter($image);

            return $image->save(
                sprintf('%s%s.%s', $path, uniqid(), $this->guessExtension(
                    $image
                ))
            );

        }, array_filter($images));
    }

    /**
     * Guess extension .
     *
     * @param Image $image
     * @return string
     */
    protected function guessExtension(Image $image) {
        $guesser = ExtensionGuesser::getInstance();

        return $guesser->guess(
            $image->mime()
        );
    }
}