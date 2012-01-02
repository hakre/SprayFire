<?php

/**
 * @file
 * @brief Holds a SprayFire.Config.Configuration object that will convert
 * a JSON configuration file into a chainable ImmutableStorage object.
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
 * @copyright Copyright (c) 2011,2012 Charles Sprayberry
 */

namespace SprayFire\Config;

/**
 * @brief Parses a JSON configuration file into a chainable object, either by object
 * or array access notation.
 *
 * @example
 *
 * <pre>
 * // JSON configuration file -- config.json
 *
 * {
 *      "app": {
 *          "version": "1.0.0-beta",
 *          "development-settings": {
 *              "debug-mode": "on",
 *              "display-errors": 1
 *          }
 *      }
 * }
 *
 * // Somewhere in your PHP code
 * $configFile = new \\SplFileInfo($filePath);
 * $Config = new \\SprayFire\\Config\\JsonConfig($configFile);
 *
 * // Values from the configuration file can now be read
 *
 * echo $Config->app->version;  // '1.0.0-beta'
 *
 * echo $Config['app']['development-settings']['debug-mode'];   // 'on'
 *
 * // I mixed these to show that it is possible.  It is not recommended however
 * echo $Config->app['development-settings']->{'display-errors'};   // 1
 * </pre>
 *
 * @uses SplFileInfo
 * @uses InvalidArgumentException
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Core.Structure.ImmutableStorage
 */
class JsonConfig extends \SprayFire\Core\Structure\ImmutableStorage implements \SprayFire\Config\Configuration {

    /**
     * @brief Holds the SplFileInfo object passed in the constructor.
     *
     * @property $ConfigFileInfo
     * @see http://us3.php.net/manual/en/class.splfileinfo.php
     */
    protected $ConfigFileInfo;

    /**
     * @brief Parses a file represented by the \a $FileInfo object and prepares
     * the JsonConfig object to have key/value pairs chainable.
     *
     * @param $FileInfo SplFileInfo object
     * @throws InvalidArgumentException
     */
    public function __construct(\SplFileInfo $FileInfo) {
        $this->ConfigFileInfo = $FileInfo;
        $data = $this->getDecodedJson();
        parent::__construct($data);
    }

    /**
     * @brief Converts the JSON configuration file passed into an associative
     * array
     *
     * @return Associative array representing key/value pairs held in JSON config
     * @throws InvalidArgumentException
     * @see http://www.php.net/manual/en/book.json.php
     */
    protected function getDecodedJson() {
        $decodedJson = \json_decode($this->getFileContents(), true);
        $lastJsonError = \json_last_error();
        if ($lastJsonError !== \JSON_ERROR_NONE || \is_null($decodedJson)) {
            throw new \InvalidArgumentException('There was an error parsing the JSON configuration file passed.  JSON_error_code := ' . $lastJsonError);
        }
        return $decodedJson;
    }

    /**
     * @return The contents of the file represented by \a $ConfigFileInfo
     * @throws InvalidArgumentException
     * @see http://www.php.net/manual/en/function.file-get-contents.php
     */
    protected function getFileContents() {
        if (!$this->ConfigFileInfo->isFile() && !$this->ConfigFileInfo->isLink()) {
            throw new \InvalidArgumentException('There is an error with the path to the configuration file, it does not appear to be a valid file or symlink.');
        }
        $fileInfo = \file_get_contents($this->ConfigFileInfo->getRealPath());
        return $fileInfo;
    }

    /**
     * @return Returns the namespaced class and sub-directory fragment for the
     * associated configuration file.
     */
    public function __toString() {
        return parent::__toString() . '::' . $this->ConfigFileInfo->getPathname();
    }

}