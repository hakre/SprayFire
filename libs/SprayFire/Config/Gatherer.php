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
namespace SprayFire\Config;

/**
 * @brief Will gather an appropriate configuration object for the primary configuration
 * and the routes configuration.
 */
class Gatherer {

    /**
     * @brief A SprayFire.Config.Configuration object that stores the primary
     * configuration used by SprayFire.
     *
     * @property $PrimaryConfig
     * @see https://github.com/cspray/SprayFire/wiki/Configuration
     */
    protected $PrimaryConfig;

    /**
     * @brief A SprayFire.Config.Configuration object that stores the routes configuration
     * used to map a SprayFireURI Pattern to a specific controller and action.
     *
     * @property $RoutesConfig
     *
     */
    protected $RoutesConfig;

    /**
     * @brief The absolute path to the primary configuration file; should be passed
     * in the constructor as there should be just one \a $PrimaryConfig
     *
     * @property $primaryConfigPath
     */
    protected $primaryConfigPath;

    /**
     * @brief The absolute path to the routes configuration file; should be passed
     * in the constructor as there should be just one \a $RoutesConfig
     *
     * @property $routesConfigPath
     */
    protected $routesConfigPath;

    /**
     * @brief Configuration used  for the PrimaryConfig object if the file does
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
     * @brief Configuration used for the RoutesConfig object if the file does not
     * exst in its expected location.
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
     * @param $primaryConfigPath Absolute path to the primary configuration file
     * @param $routesConfigPath Absolute path to the routes configuration file
     */
    public function __construct($primaryConfigPath, $routesConfigPath) {
        $this->primaryConfigPath = $primaryConfigPath;
        $this->routesConfigPath = $routesConfigPath;
    }

    /**
     * @param $primaryConfigPath Complete path to the primary config file
     * @return SprayFire.Config.Configuration
     */
    public function fetchPrimaryConfiguration() {
        if (!isset($this->PrimaryConfig)) {
            $File = new \SplFileInfo(\SprayFire\Core\Directory::getLibsPath($this->primaryConfigPath));
            try {
                $this->PrimaryConfig = new \SprayFire\Config\JsonConfig($File);
            } catch (\InvalidArgumentException $InvalArgExc) {
                $this->PrimaryConfig = new \SprayFire\Config\ArrayConfig($this->defaultPrimaryConfiguration);
            }
        }

        return $this->PrimaryConfig;
    }

    /**
     * @param $routesConfigPath Complete path to the routes config file
     * @return SprayFire.Config.Configuration
     */
    public function fetchRoutesConfiguration() {
        if (!isset($this->RoutesConfig)) {
            $File = new \SplFileInfo(\SprayFire\Core\Directory::getLibsPath($this->routesConfigPath));
            try {
                $this->RoutesConfig = new \SprayFire\Config\JsonConfig($File);
            } catch (\InvalidArgumentException $InvalArgExc) {
                $this->RoutesConfig = new \SprayFire\Config\ArrayConfig($this->defaultRoutesConfiguration);
            }
        }

        return $this->RoutesConfig;
    }

}