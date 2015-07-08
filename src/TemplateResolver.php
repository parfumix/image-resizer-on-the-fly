<?php

namespace Parfumix\Imageonfly;

use Illuminate\Config\Repository;
use Parfumix\Imageonfly\Interfaces\TemplateResolverInterface;

class TemplateResolver implements TemplateResolverInterface  {

    /**
     * @var array
     */
    private $templates;

    public function __construct(Repository $templates) {

        $this->templates = $templates;
    }


    /**
     * Resolve path ..
     *
     * @param $alias
     * @return mixed
     */
    public function resolve($alias) {
        if( $this->templates->has($alias) )
            return $this->templates->get($alias);
    }
}