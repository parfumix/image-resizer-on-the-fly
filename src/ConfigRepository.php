<?php

namespace Parfumix\Imageonfly;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Config\Repository;

class ConfigRepository {

    protected static $configurations = array();

    /**
     * Set configurations .
     *
     * @return array
     */
    public static function getConfigurations() {
        if(! self::$configurations) {
            self::$configurations = self::parseConfigurations();
        }

        return self::$configurations;
    }

    /**
     * @param array $configurations
     * @return bool
     */
    public static function setConfigurations(array $configurations) {
        self::$configurations = $configurations;

        return true;
    }

    /**
     * Parse yaml configurations ..
     *
     * @return Repository
     */
    protected static function parseConfigurations() {
        $configurations =  Yaml::parse(file_get_contents(
            configPath(ImageOnflyServiceProvider::CONFIG_PATH)
        ));

        return (new Repository($configurations));
    }
}