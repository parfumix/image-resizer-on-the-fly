<?php

namespace Parfumix\Imageonfly;

use AndyTruong\Yaml\YamlParser;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;
use Parfumix\Imageonfly\Interfaces\ImageManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Intervention\Image\ImageManager as ManagerImage;

require_once(__DIR__ . '/../helpers.php');

class ImageManager implements ImageManagerInterface {

    const PATH_TO_FILTERS = 'yaml/upload/templates.yaml';

    const PATH_TO_STORE = 'upload/static';

    protected $parser;

    protected $template;

    protected $templateAlias;

    protected $originalImage;

    protected $processedImage;

    protected $isProcessedImage = false;

    protected $corePath;

    protected $sourcePath;

    protected $fileSystem;

    /**
     * @param $image
     * @param $templateAlias
     * @throws ImageManagerException
     */
    public function __construct($image, $templateAlias) {
        $this->fileSystem = new Filesystem();
        $this->imageManager = new ManagerImage(array('driver' => 'gd'));

        $template = $this->getTemplateAlias($templateAlias);

        $this->setTemplate($template, $templateAlias)
            ->setOriginalImage($image);
    }


    /**
     * Set template filter .
     *
     * @param FilterInterface $template
     * @param $alias
     * @return $this
     */
    public function setTemplate(FilterInterface $template, $alias) {
        $this->template = $template;
        $this->templateAlias = $alias;

        return $this;
    }

    /**
     * Get template ..
     *
     * @return mixed
     */
    public function getTemplate() {
        return $this->template;
    }


    /**
     * Set original image ..
     *
     * @param $originalImage
     * @return $this
     */
    public function setOriginalImage($originalImage) {
        $this->originalImage = $this->imageManager->make(
            publicPath($originalImage)
        );

        $this->originalImage->originalPath = $originalImage;

        return $this;
    }

    /**
     * Get original image ..
     *
     * @return mixed
     */
    public function getOriginalImage() {
        return $this->originalImage;
    }

    /**
     * Apply current template to image ..
     *
     * @return $this
     */
    public function applyTemplate() {
        $image = $this->getOriginalImage();

        $image->filter(
            $this->getTemplate()
        );

        $this->setProcessedImage($image);

        return $this;
    }

    /**
     * Check if image is processed .
     *
     * @return bool
     */
    public function isProcessedImage() {
        return $this->isProcessedImage;
    }

    /**
     * Get image resource instance ..
     *
     * @return mixed|null
     */
    public function getResource() {
        if (!$this->isProcessedImage())
            return null;

        return $this->getOriginalImage();
    }

    /**
     * Store image to given path ..
     *
     * @param null $path
     * @param int $quality
     * @return
     * @throws ImageManagerException
     */
    public function store($path = null, $quality = 60) {
        if (!$this->isProcessedImage())
            throw new ImageManagerException(_('Image are not processed.'));

        if (is_null($path)) {
            $rawOriginalPath = pathinfo($this->getOriginalImage()->originalPath);

            $path = self::PATH_TO_STORE . DIRECTORY_SEPARATOR . sprintf('%s/%s_%s', $rawOriginalPath['dirname'], $this->templateAlias, $rawOriginalPath['basename']);
        } else {
            $path = self::PATH_TO_STORE . $path . DIRECTORY_SEPARATOR . sprintf('%s_%s.%s', $this->templateAlias, $this->getOriginalImage()->filename, $this->getOriginalImage()->extension);
        }

        $fullPath = publicPath($path);

        $pathRaw = pathinfo($fullPath);

        if (!$this->fileSystem->exists($pathRaw['dirname']))
            $this->fileSystem->mkdir($pathRaw['dirname']);

        return $this->getOriginalImage()
            ->save($fullPath, $quality);
    }

    /**
     * Render image ..
     *
     * @throws ImageManagerException
     */
    public function render() {
        $rawOriginalPath = pathinfo($this->getOriginalImage()->originalPath);

        $pathToStatic = publicPath(
            self::PATH_TO_STORE . DIRECTORY_SEPARATOR . sprintf('%s/%s_%s', $rawOriginalPath['dirname'], $this->templateAlias, $rawOriginalPath['basename'])
        );

        if (!$this->fileSystem->exists(
            $pathToStatic
        )
        ) {
            $image = $this->applyTemplate()
                ->store();

            echo $image->response();
        } else {
            $image = $this->imageManager->make(
                $pathToStatic
            );

            echo $image->response();
        }
    }

    /**
     * Set Parser ..
     *
     * @param $parser
     * @return $this
     */
    protected function setParser($parser) {
        $this->parser = $parser;

        return $this;
    }

    /**
     * Get parser ..
     *
     * @return YamlParser
     */
    protected function getParser() {
        if (!$this->parser)
            $this->parser = new YamlParser();

        return $this->parser;
    }

    /**
     * Get template instance ..
     *
     * @param $aliasTemplate
     * @return mixed
     * @throws ImageManagerException
     */
    protected function getTemplateAlias($aliasTemplate) {
        if (! configPath(self::PATH_TO_FILTERS))
            throw new ImageManagerException(
                _('Invalid path to filters.')
            );

        $filters = $this->getParser()
            ->parse(
                file_get_contents(configPath(self::PATH_TO_FILTERS))
            );

        if (! isset($filters[strtolower($aliasTemplate)]))
            throw new ImageManagerException(_('Invalid template alias'));

        $class = $filters[strtolower($aliasTemplate)];

        return (new $class);
    }

    /**
     * Set processed image ..
     *
     * @param Image $image
     * @return $this
     */
    protected function setProcessedImage(Image $image) {
        $this->processedImage = $image;

        $this->isProcessedImage = true;

        return $this;
    }
}

