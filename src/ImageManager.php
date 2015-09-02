<?php

namespace Parfumix\Imageonfly;

use Parfumix\Imageonfly\Interfaces\ImageManagerInterface;
use Parfumix\Imageonfly\Interfaces\ImageProcessorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once(__DIR__ . '/../helpers.php');

class ImageManager implements ImageManagerInterface {

    /**
     * @var ImageProcessorInterface
     */
    private $imageProcessor;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @param ImageProcessorInterface $imageProcessor
     * @param ConfigRepository $configRepository
     */
    public function __construct(ImageProcessorInterface $imageProcessor, ConfigRepository $configRepository) {

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
            ->get('static_path');

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
            ->get('static_path');

        #@todo check for first if file exists ..

        $images = $this->imageProcessor
            ->upload($image, publicPath($storePath), [$template]);

        echo arrayFirst($images)
            ->response();
    }
}

