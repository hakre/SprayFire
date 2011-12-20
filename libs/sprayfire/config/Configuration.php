<?php

/**
 * @file
 * @brief Holds an interface that requires the injection of a SplFileInfo object
 * representing the configuration info.
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

/**
 * @namespace libs.sprayfire.config
 * @brief Holds interfaces and classes that convert configuration information into
 * something usable for the application and framework.
 */
namespace libs\sprayfire\config {

    /**
     * @brief An interface that requires implementing objects to accept a SplFileInfo
     * object representing a configuration file and that the values in that configuration
     * file should be accessible through object or array notation.
     */
    interface Configuration extends \ArrayAccess, \libs\sprayfire\datastructs\Overloadable {

        /**
         * @brief Requires the injection of a SplFileInfo object
         *
         * @param $FileInfo SplFileInfo holding the path to the configuration file
         */
        public function __construct(\SplFileInfo $FileInfo);

    }

    // End Configuration

}

// End libs.sprayfire.config