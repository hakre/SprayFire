<?php

/**
 * @file
 * @brief Holds an interface that requires the injection of a SplFileInfo object
 * representing the configuration info.
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

/**
 * @namespace libs.sprayfire.config
 * @brief Holds interfaces and classes that convert configuration information into
 * something usable for the application and framework.
 */
namespace libs\sprayfire\config;
use \ArrayAccess as ArrayAccess;
use \SplFileInfo as SplFileInfo;
use libs\sprayfire\datastructs\Overloadable as Overloadable;

    /**
     * @brief An interface that requires implementing objects to accept a SplFileInfo
     * object representing a configuration file and that the values in that configuration
     * file should be accessible through object or array notation.
     */
    interface Configuration extends ArrayAccess, Overloadable {

        /**
         * @brief Requires the injection of a SplFileInfo object
         *
         * @param $FileInfo SplFileInfo holding the path to the configuration file
         */
        public function __construct(SplFileInfo $FileInfo);

    }

    // End Configuration