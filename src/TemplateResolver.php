<?php

namespace Parfumix\Imageonfly;

use Parfumix\Imageonfly\Interfaces\TemplateResolverInterface;

class TemplateResolver implements TemplateResolverInterface  {

    /**
     * @var array
     */
    private $templates;

    public function __construct(array $templates = array()) {

        $this->templates = $templates;
    }


    /**
     * Resolve path ..
     *
     * @param $alias
     * @return mixed
     */
    public function resolve($alias) {
        return $this->templates[$alias];
    }
}