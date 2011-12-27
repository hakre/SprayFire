<?php

/**
 * @file
 * @brief Holds an interface for implementing objects that are responsible for framework
 * or app bootstrap functions.
 *
 * @detials
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

namespace libs\sprayfire\bootstrap;

    /**
     * @brief An interface implemented by all framework and app bootstrapping objects.
     */
    interface Bootstrapper {

        /**
         * @brief Every bootstrapper should be prepared to accept a configuration object, as
         * many of the responsibilities for the bootstrapping object is to ensure
         * configuration values are properly set.
         *
         * @param $ConfigurationObject libs.sprayfire.config.Configuration
         */
        public function __construct(\libs\sprayfire\config\Configuration $ConfigurationObject);

        /**
         * @brief A method that should do whatever bootstrapping features are needed
         * for that particular bootstrap.
         */
        public function runBootstrap();

    }