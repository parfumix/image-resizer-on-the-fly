<?php

use Parfumix\Imageonfly;
use \Symfony\Component\HttpFoundation\File;

require __DIR__.'/../vendor/autoload.php';

if(! isset($_GET['static']) )
    return;

$staticImage = $_GET['static'];

list($alias, $original) = explode('@', $staticImage);
$raw                    = pathinfo($alias);

$configurations = Imageonfly\ConfigRepository::getConfigurations();

$processor = new Imageonfly\ImageProcessor(
    $configurations, (new Imageonfly\TemplateResolver(
        array_only($configurations, ['templates'])
    ))
);

(new Imageonfly\ImageManager($processor, $configurations))
    ->render(
        new File\UploadedFile($original, 'tempname'), $raw['filename']
    );