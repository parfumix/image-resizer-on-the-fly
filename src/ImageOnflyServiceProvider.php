<?php

namespace Parfumix\Imageonfly;

use Illuminate\Support\ServiceProvider;
use Flysap\Support;
use Intervention\Image\ImageServiceProviderLaravel5;

class ImageOnFlyServiceProvider extends ServiceProvider {

    /**
     * Publish resources.
     */
    public function boot() {
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '../configuration/' => config_path('yaml/imageonfly'),
            __DIR__ . DIRECTORY_SEPARATOR . '../image.php' => public_path()
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->loadConfiguration();

        Support\merge_yaml_config_from(
            config_path('yaml/imageonfly/general.yaml') , 'image-on-fly'
        );

        $this->registerDependencies();

        /**
         * Register template resolver .
         */
        $this->app->singleton('image-template-resolver', function() {
            return new TemplateResolver(
                config('image-on-fly')
            );
        });

        /**
         * Register image processor to Ioc.
         */
        $this->app->singleton('image-processor', function($app) {
            return new ImageProcessor(
                config('image-on-fly'),
                $app['image-template-resolver']
            );
        });
    }

    /**
     * Register service provider dependencies .
     *
     */
    protected function registerDependencies() {
        $dependencies = [ImageServiceProviderLaravel5::class];

        array_walk($dependencies, function($dependency) {
            app()->register($dependency);
        });
    }

    /**
     * Load configuration .
     *
     * @return $this
     */
    protected function loadConfiguration() {
        Support\set_config_from_yaml(
            __DIR__ . '/../configuration/general.yaml' , 'image-on-fly'
        );

        return $this;
    }
}