<?php

/**
 * @file
 * @brief A class that checks for various settings that are necessary for a smooth
 * processing of a request.
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
 * @brief Will check various settings to ensure SprayFire sanity.
 *
 * @see https://github.com/cspray/SprayFire/issues?milestone=7
 */
class SanityCheck {

    /**
     * @brief The absolute path to the logsPath set by \SprayFire\Core\Directory
     *
     * @property $logsPath
     */
    protected $logsPath;

    /**
     * @brief The absolute path and filename to the primary configuration file
     * used by the framework.
     *
     * @property $primaryConfigurationPath
     */
    protected $primaryConfigurationPath;

    /**
     * @brief An array holding messages regarding failed sanity checks
     *
     * @property $errors
     */
    protected $errors;

    /**
     * @brief Sets various data that is used for various sanity checks
     */
    public function __construct() {
        $this->logsPath = \SprayFire\Core\Directory::getLogsPath();
        $this->primaryConfigurationPath = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json');
        $this->errors = array();
    }

    /**
     * @return An array of error messages that were populated by various checks
     */
    public function verifySanity() {
        $this->checkLogsPathWritable();
        $this->checkPrimaryConfigurationExists();
        return $this->errors;
    }

    /**
     * @brief Will check various settings of the \a $logsPath directory to ensure
     * it is set and the directory is writable.
     */
    protected function checkLogsPathWritable() {
        if (!isset($this->logsPath)) {
            $this->errors[] = 'The logs path was not set properly, please ensure you invoke \\SprayFire\\Core\\Directory::setLibsPath()';
            return;
        }
        if (!\is_writable($this->logsPath)) {
            $this->errors[] = 'The logs path set, ' . $this->logsPath . ', is not writable.  Please change the permissions on this directory to allow writing.';
        }
    }

    /**
     * @brief Will check to see if the \a $primaryConfigurationPath file is set
     * and exists.
     */
    protected function checkPrimaryConfigurationExists() {
        if (!isset($this->primaryConfigurationPath)) {
            $this->errors[] = 'The libs path may not have been set via \\SprayFire\\Core\\Directory::setLibsPath()';
            return;
        }
        if (!\file_exists($this->primaryConfigurationPath)) {
            $this->errors[] = 'The primary configuration path, ' . $this->primaryConfigurationPath . ', does not exist.  Please create the appropriate configuration file.';
        }
    }

}