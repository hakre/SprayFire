<?php

/**
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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\interfaces;

/**
 * An interface that should be implemented by objects that determine the framework
 * and application paths.
 *
 * Ultimately, this interface is used to better facilitate unit testing of the
 * framework bootstrap.
 */
interface FrameworkPaths {

    /**
     * The string passed will be the complete path to the framework's root folder.
     *
     * @param string
     */
    public static function setRootInstallationPath($rootInstallationPath);

    /**
     * Should return a string with the complete path to the framework's root
     * directory.
     *
     * @return string
     */
    public static function getFrameworkPath();

    /**
     * Should return a string with the complete path to the application's root
     * directory.
     *
     * @return string
     */
    public static function getAppPath();

    /**
     * Should return a string with the complete path to the sub directory of the
     * framework; this list of dir should be a variable number of string arguments.
     *
     * @return string
     */
    public static function getFrameworkPathSubDirectory();

    /**
     * Should return a string with the complete path to the sub directory of the
     * application; the list of dir should be a variable number of string arguments.
     *
     * @return string
     */
    public static function getAppPathSubDirectory();

}

// End FrameworkPaths
