<?php

/**
 * @file
 * @brief A logger that stores information in an array suitable for development
 * debugging storage.
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
 * @brief A more flexible implementation of the SprayFire.Logger.Log interface that
 * allows for much more information to be logged about a particular event.
 */
class DevelopmentLogger extends \SprayFire\Core\CoreObject implements \SprayFire\Logger\Log {

    /**
     * @brief A storage of the messages that are passed to the logger
     *
     * @property $messages
     */
    protected $messages = array();

    /**
     * @param $timestamp The time of the log
     * @param $message The message to log
     */
    public function log($timestamp, $message) {
        $index = \count($this->messages);
        $this->messages[$index]['timestamp'] = $timestamp;
        $this->messages[$index]['info'] = $message;
    }

    /**
     * @return an array of the messages logged
     */
    public function getMessages() {
        return $this->messages;
    }

}