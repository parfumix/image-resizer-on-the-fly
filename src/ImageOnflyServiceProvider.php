<?php

namespace Parfumix\Imageonfly;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class ImageOnflyServiceProvider extends ServiceProvider {

    protected $configuration = array();

    /**
     * Publish resources.
     */
    public function boot() {
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '../config' => config_path('yaml/imageonfly'),
            __DIR__ . DIRECTORY_SEPARATOR . '../image.php' => public_path()
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(ImageProcessorInterface::class, function() {
            return new ImageProcessor(
                $this->getConfiguration()
            );
        });
    }

    /**
     * Parse package configuration ..
     */
    protected function getConfiguration() {
        if(! $this->configuration) {
            $parsedYaml =  Yaml::parse(file_get_contents(
                config('yaml/imageonfly')
            ));

            $this->configuration = $parsedYaml;
        }

        return $this->configuration;
    }
}