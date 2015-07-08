<?php

namespace Parfumix\Imageonfly\Interfaces;

interface TemplateResolverInterface  {

    /**
     * Resolve path ..
     *
     * @param $alias
     * @return mixed
     */
    public function resolve($alias);
}