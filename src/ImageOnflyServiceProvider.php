<?php

namespace Parfumix\Imageonfly;

use Illuminate\Support\ServiceProvider;

class ImageOnflyServiceProvider extends ServiceProvider {

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
            return new ImageProcessor();
        });
    }
}