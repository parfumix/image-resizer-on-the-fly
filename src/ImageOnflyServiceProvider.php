<?php

namespace Parfumix\Imageonfly;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Parfumix\Imageonfly\Interfaces\ImageProcessorInterface;
use Parfumix\Imageonfly\Interfaces\TemplateResolverInterface;
use Symfony\Component\Yaml\Yaml;

class ImageOnflyServiceProvider extends ServiceProvider {

    protected static $configuration = array();

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

        /**
         * Register template resolver .
         */
        $this->app->singleton(TemplateResolverInterface::class, function() {
            return new TemplateResolver(
                new Repository(
                    self::getConfiguration()
                        ->get('templates')
                )
            );
        });

        /**
         * Register image processor to Ioc.
         */
        $this->app->singleton(ImageProcessorInterface::class, function($app) {
            return new ImageProcessor(
                self::getConfiguration(), $app[TemplateResolverInterface::class]
            );
        });


    }

    /**
     * Parse package configuration ..
     */
    protected static function getConfiguration() {
        if(! self::$configuration) {
            $configurations =  Yaml::parse(file_get_contents(
                config('yaml/imageonfly')
            ));

            self::$configuration = new Repository($configurations);
        }

        return self::$configuration;
    }
}