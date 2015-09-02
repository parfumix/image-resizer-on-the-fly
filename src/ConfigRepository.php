<?php

namespace Parfumix\Imageonfly;

use Symfony\Component\Yaml\Yaml;

class ConfigRepository {

    const CONFIG_FILE = 'yaml/imageonfly/general.yaml';

    protected $configurations = [];

    public function __construct() {
        $userConfigurations = Yaml::parse(file_get_contents(
            configPath(self::CONFIG_FILE)
        ));

        $localConfigurations = Yaml::parse(file_get_contents(
            __DIR__ . '../configuration/general.yaml'
        ));

        $this->setConfigurations(
            array_merge($userConfigurations, $localConfigurations)
        );
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