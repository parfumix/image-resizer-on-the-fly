<?php

namespace Parfumix\Imageonfly\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageManagerInterface {

    public function render(UploadedFile $image, $template);

    public function store(UploadedFile $image, $template);
}