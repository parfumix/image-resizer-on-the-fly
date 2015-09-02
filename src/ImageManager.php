<?php

namespace Parfumix\Imageonfly;

use Parfumix\Imageonfly\Interfaces\ImageManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager implements ImageManagerInterface {

    /**
     * @var
     */
    private $imageProcessor;

    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct($imageProcessor, ConfigRepository $configRepository) {

        $this->imageProcessor = $imageProcessor;
        $this->configRepository = $configRepository;
    }

    /**
     * @param UploadedFile $image
     * @param $template
     * @return mixed
     */
    public function store(UploadedFile $image, $template) {
        $storePath = $this->configRepository
            ->getConfiguration('static_path');

        return $this->imageProcessor
            ->upload($image, publicPath($storePath), [$template]);
    }

    /**
     * Render image ..
     *
     * @param UploadedFile $image
     * @param $template
     */
    public function render(UploadedFile $image, $template) {
        $storePath = $this->configRepository
            ->getConfiguration('static_path');

        #@todo check for first if file exists ..

        $images = $this->imageProcessor
            ->upload($image, publicPath($storePath), [$template]);

        echo arrayFirst($images)
            ->response();
    }
}

