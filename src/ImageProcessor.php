<?php

namespace Parfumix\Imageonfly;

use Image as Imager;
use Intervention\Image\Image;
use Parfumix\Imageonfly\Exceptions\ImageProcessorException;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class ImageProcessor {

    /**
     * @var array
     */
    private $configurations;

    /**
     * @var
     */
    private $templateResolver;

    public function __construct($configurations, $templateResolver) {

        $this->configurations    = $configurations;
        $this->templateResolver = $templateResolver;
    }

    /**
     * Upload array of images ..
     *
     * @param $images
     * @param $path
     * @param array $filters
     * @return array
     */
    public function upload($images, $path = null, array $filters = []) {
        if (! is_array($images))
            $images = (array)$images;

        return array_map(function ($image) use ($path, $filters) {
            $image = Imager::make($image);

            $image = $this->applyFilters($image, $filters);

            if( is_null($path) )
                $path = $this->getPath();

            return $image->save(
                sprintf('%s/%s.%s', $path, uniqid(), $this->guessExtension(
                    $image
                )), $this->getQuality()
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

    /**
     * Apply filters .
     *
     * @param Image $image
     * @param array $filters
     * @return Image
     */
    public function applyFilters(Image $image, array $filters) {
        foreach ($filters as $filter) {
            $filterClass = $this->templateResolver[$filter];

            if( class_exists($filterClass) )
                $image = (new $filterClass)->applyFilter($image);
        }

        return $image;
    }

    /**
     * Get default quality .
     *
     * @param int $default
     * @return bool|int
     */
    protected function getQuality($default = 60) {
        return isset($this->configurations['quality']) ? $this->configurations['quality'] : $default;
    }

    /**
     * Get default path ..
     *
     * @return string
     * @throws ImageProcessorException
     */
    protected function getPath() {
        if( ! isset($this->configurations['store_path']) )
            throw new ImageProcessorException(_('Invalid store path'));

        return publicPath($this->configurations['store_path']);
    }
}