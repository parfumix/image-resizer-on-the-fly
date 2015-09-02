<?php

namespace Parfumix\Imageonfly;

use Illuminate\Support\ServiceProvider;

class ImageOnflyServiceProvider extends ServiceProvider {

    const CONFIG_PATH = 'yaml/imageonfly/configuration.yaml';

    /**
     * Publish resources.
     */
    public function boot() {
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '../config' => config_path(self::CONFIG_PATH),
            __DIR__ . DIRECTORY_SEPARATOR . '../image.php' => public_path()
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $configurations = ConfigRepository::getConfigurations();

        /**
         * Register template resolver .
         */
        $this->app->singleton('image-template-resolver', function() use($configurations) {
            return new TemplateResolver($configurations);
        });

        /**
         * Register image processor to Ioc.
         */
        $this->app->singleton('image-processor', function($app) use($configurations) {
            return new ImageProcessor($configurations, $app['image-template-resolver']
            );
        });
    }
}