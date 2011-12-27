<?php

/**
 * @file
 * @brief Holds the interface to be implemented by objects responsible for logging
 * messages and data.
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

namespace libs\sprayfire\logger;

    /**
     * @brief An interface for implementing objects that should be responsible for
     * logging various messages to disk.
     */
    interface Logger {

        /**
         * @param $timestamp the timestamp for the given message
         * @param $message The string message that should be appended to the end
         *        of the log
         */
        public function log($timestamp, $message);

    }