<?php

namespace Parfumix\Imageonfly;

class TemplateResolver {

    /**
     * @var array
     */
    private $templates;

    public function __construct($templates) {
        $this->templates = $templates;
    }

    /**
     * Resolve path ..
     *
     * @param $alias
     * @return mixed
     */
    public function resolve($alias) {
        if( isset($this->templates[$alias]) )
            return $this->templates[$alias];
    }
}