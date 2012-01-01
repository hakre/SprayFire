<?php

/**
 * @file
 * @brief Holds a class that implements error logging at a very basic PHP level
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
namespace SprayFire\Logger;

/**
 * @brief A SprayFire.Logger.Log implementation that utilizes the system error log
 * to store error messages.
 *
 * @uses SprayFire.Logger.Log
 */
class SystemLogger implements \SprayFire\Logger\Log {

    /**
     * @brief An implementation that uses PHP built in <code>error_log</code>
     * function
     *
     * @param $timestamp The timestamp for the \a $message being logged
     * @param $message The message to be logged
     * @see http://php.net/manual/en/function.error-log.php
     */
    public function log($timestamp, $message) {
        \error_log($timestamp . ' := ' . $message);
    }

}