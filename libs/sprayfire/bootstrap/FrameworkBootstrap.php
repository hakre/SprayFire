<?php

/**
 * @file
 * @brief Holds the class that takes care of framework bootstrapping procedures
 * and instantiates and invokes the application bootstraps.
 *
 * @details
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

namespace SprayFire\Bootstrap;

/**
 * @brief The framework's bootstrap, implementing necessary startup details
 * for the framework and the application.
 */
class FrameworkBootstrap extends \SprayFire\Core\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief The application's primary configuration file, converted into
     * an appropriate object.
     *
     * @property $FrameworkConfiguration SprayFire.Config.Configuration
     * @see https://github.com/cspray/SprayFire/wiki/Configuration
     */
    private $FrameworkConfiguration;

    /**
     * @brief Requires the injection of a SprayFire.Config.Configuration
     * object for necessary framework and application configuration details.
     *
     * @param $FrameworkConfiguration SprayFire.Config.Configuration
     */
    public function __construct(\SprayFire\Config\Configuration $FrameworkConfiguration) {
        $this->FrameworkConfiguration = $FrameworkConfiguration;
    }

    /**
     * @brief Writes the basic configuration files and runs the application's
     * bootstrap.
     */
    public function runBootstrap() {
    }

}