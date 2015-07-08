<?php

namespace Parfumix\Imageonfly;

use Illuminate\Config\Repository;
use Image as Imager;
use Intervention\Image\Image;
use Parfumix\Imageonfly\Exceptions\ImageProcessorException;
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

    public function __construct(Repository $configuration, TemplateResolverInterface $templateResolver) {

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
    public function upload($images, $path = null, array $filters = []) {
        if (! is_array($images))
            $images = (array)$images;

        return array_map(function ($image) use ($path, $filters) {
            $image = Imager::make($image);

            $image = $this->applyFilters($image, $filters);

            if( is_null($path) ) {
                if( ! $this->configuration->has('store_path') )
                    throw new ImageProcessorException(_('Invalid store path'));

                $path = \App\Library\Image\public_path($this->configuration->get('store_path'));
            }

            return $image->save(
                sprintf('%s%s.%s', $path, uniqid(), $this->guessExtension(
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
        if(! $quality = $this->configuration->has('quality'))
            return $default;

        return $quality;
    }
}