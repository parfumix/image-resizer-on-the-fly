<?php

namespace Parfumix\Imageonfly;

use Symfony\Component\Yaml\Yaml;

class ConfigRepository {

    const CONFIG_FILE = 'yaml/imageonfly/configuration.yaml';

    protected $configurations = [];

    public function __construct() {
        $configurations = Yaml::parse(file_get_contents(
            configPath(self::CONFIG_FILE)
        ));

        $this->setConfigurations($configurations);
    }

    /**
     * Set configurations .
     *
     * @param array $configurations
     * @return $this
     */
    public function setConfigurations(array $configurations = array()) {
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * Get all configurations .
     *
     * @return array
     */
    public function getConfigurations() {
        return $this->configurations;
    }

    /**
     * Get configuration by key .
     *
     * @param $key
     * @return mixed
     */
    public function getConfiguration($key) {
        if( isset($this->configurations[$key]) )
            return $this->configurations[$key];
    }
}