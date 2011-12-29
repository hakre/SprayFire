<?php

/**
 * @file
 * @brief Holds a class that gathers the various configuration objects used by
 * SprayFire
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */
namespace SprayFire\Core;

/**
 * @brief Will gather an appropriate configuration object for the primary configuration
 * and the routes configuration.
 */
class ConfigGatherer {

    /**
     * @brief The values set for the primary configuration object if the file does
     * not exist in its expected location.
     *
     * @property $defaultPrimaryConfiguration
     */
    protected $defaultPrimaryConfiguration = array(
        'framework' => array(
            'version' => '0.1.0-alpha'
        ),
        'app' => array(
            'version' => '0.0.0-e',
            'development-mode' => 'off'
        )
    );

    /**
     * @brief The value set for the routes configuration object if the file does
     * not exst in its expected location
     *
     * @property $defaultRoutesConfiguration
     */
    protected $defaultRoutesConfiguration = array(
        'defaults' => array(
            'controller' => 'pages',
            'action' => 'index'
        )
    );

    /**
     * @param $primaryConfigPath Complete path to the primary config file
     * @return SprayFire.Config.Configuration
     */
    public function fetchPrimaryConfiguration($primaryConfigPath) {
        $File = new \SplFileInfo(\SprayFire\Core\Directory::getLibsPath($primaryConfigPath));
        try {
            $Config = new \SprayFire\Config\JsonConfig($File);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $Config = new \SprayFire\Config\ArrayConfig($this->defaultPrimaryConfiguration);
        }
        return $Config;
    }

    /**
     *
     * @param $routesConfigPath Complete path to the routes config file
     * @return SprayFire.Config.Configuration
     */
    public function fetchRoutesConfiguration($routesConfigPath) {
        $File = new \SplFileInfo(\SprayFire\Core\Directory::getLibsPath($routesConfigPath));
        try {
            $Config = new \SprayFire\Config\JsonConfig($File);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $Config = new \SprayFire\Config\ArrayConfig($this->defaultRoutesConfiguration);
        }
        return $Config;
    }

}