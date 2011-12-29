<?php

/**
 * @file
 * @brief Holds an extension of SprayFire.Core.CoreObject to provide a single method
 * to log various messages.
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
 * @brief A CoreObject that allows for extending classes to log messages via a
 * `log()` method.
 *
 * @details
 * By default this method will log a message with the current timestamp at the time
 * of call.  The timestamp format by default is `M-d-Y H:i:s` but you can change
 * the timestamp format by passing the optional second parameter on construction.
 */
abstract class LoggableCoreObject extends \SprayFire\Core\CoreObject {

    /**
     * @brief A SprayFire.Logger.Log object that is used to store various messages.
     *
     * @property $Log
     */
    protected $Log;

    /**
     * @brief The date format string to use for log messages
     *
     * @property $timestampFormat
     * @see http://php.net/manual/en/function.date.php
     */
    protected $timestampFormat = 'M-d-Y H:i:s';

    /**
     * @param $Log \SprayFire\Logger\Log
     * @param $timeStampFormat A string representing the format to use for log timestamps
     */
    public function __construct(\SprayFire\Logger\Log $Log, $timeStampFormat = null) {
        $this->Log = $Log;
        if (isset($timeStampFormat)) {
            $this->timeStampFormat = $timeStampFormat;
        }
    }

    /**
     * @param $message A string message to log
     */
    protected function log($message) {
        $timestamp = \date($this->timestampFormat);
        $this->Log->log($timestamp, $message);
    }

}