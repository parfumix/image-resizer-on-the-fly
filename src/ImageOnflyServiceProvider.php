<?php

namespace Parfumix\Imageonfly;

use Illuminate\Support\ServiceProvider;
use Parfumix\Imageonfly\Interfaces\ImageProcessorInterface;
use Parfumix\Imageonfly\Interfaces\TemplateResolverInterface;


class ImageOnflyServiceProvider extends ServiceProvider {

    const CONFIG_PATH = 'yaml/imageonfly';

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
        $this->app->singleton(TemplateResolverInterface::class, function() use($configurations) {
            return new TemplateResolver($configurations);
        });

        /**
         * Register image processor to Ioc.
         */
        $this->app->singleton(ImageProcessorInterface::class, function($app) use($configurations) {
            return new ImageProcessor($configurations, $app[TemplateResolverInterface::class]
            );
        });
    }
}