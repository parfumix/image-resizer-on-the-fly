<?php

namespace Parfumix\Imageonfly;

use Image as Imager;
use Intervention\Image\Image;
use Parfumix\Imageonfly\Interfaces\ImageProcessorInterface;
use Parfumix\Imageonfly\Interfaces\TemplateResolverInterface;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class ImageProcessor implements ImageProcessorInterface {

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var TemplateResolverInterface
     */
    private $templateResolver;

    public function __construct(array $configuration = array(), TemplateResolverInterface $templateResolver) {

        $this->configuration = $configuration;
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
    public function upload($images, $path, array $filters = []) {
        if (! is_array($images))
            $images = (array)$images;

        return array_map(function ($image) use ($path, $filters) {
            $image = Imager::make($image);

            $this->applyFilters($image, $filters);

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

    /**
     * Apply filters .
     *
     * @param Image $image
     * @param array $filters
     * @return Image
     */
    protected function applyFilters(Image $image, array $filters) {
        foreach ($filters as $filter) {
            $filterClass = $this->templateResolver[$filter];

            if( class_exists($filterClass) )
                $image = (new $filterClass)->applyFilter($image);
        }

        return $image;
    }
}