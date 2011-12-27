<?php

/**
 * @file
 * @brief Holds a libs.sprayfire.config.Configuration object that will convert
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
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\config;
use \SplFileInfo as SplFileInfo;
use \InvalidArgumentException as InvalidArgumentException;
use libs\sprayfire\config\Configuration as Configuration;
use libs\sprayfire\datastructs\ImmutableStorage as ImmutableStorage;

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
 * $Config = new \\libs\\sprayfire\\config\\JsonConfig($configFile);
 *
 * // Values from the configuration file can now be read
 *
 * echo $Config->app->version;  // '1.0.0-beta'
 *
 * echo $Config['app']['development-settings']['debug-mode'];   // 'on'
 *
 * echo $Config->app->{'development-settings'}->{'display-errors'};   // 1
 * </pre>
 */
class JsonConfig extends ImmutableStorage implements Configuration {

    /**
     * @brief Holds the SplFileInfo object passed in the constructor.
     *
     * @property $ConfigFileInfo
     * @see http://us3.php.net/manual/en/class.splfileinfo.php
     */
    private $ConfigFileInfo;

    /**
     * @brief Parses a file represented by the \a $FileInfo object and prepares
     * the JsonConfig object to have key/value pairs chainable.
     *
     * @param $FileInfo SplFileInfo object
     * @throws InvalidArgumentException
     */
    public function __construct(SplFileInfo $FileInfo) {
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
     */
    private function getDecodedJson() {
        $decodedJson = \json_decode($this->getFileContents(), true);
        $lastJsonError = \json_last_error();
        if ($lastJsonError !== JSON_ERROR_NONE || \is_null($decodedJson)) {
            throw new InvalidArgumentException('There was an error parsing the JSON configuration file passed.  JSON_error_code := ' . $lastJsonError);
        }
        return $decodedJson;
    }

    /**
     * @return The contents of the file represented by \a $ConfigFileInfo
     * @throws InvalidArgumentException
     */
    private function getFileContents() {
        if (!$this->ConfigFileInfo->isFile() && !$this->ConfigFileInfo->isLink()) {
            throw new InvalidArgumentException('There is an error with the path to the configuration file.');
        }
        $fileInfo = \file_get_contents($this->ConfigFileInfo->getRealPath());
        return $fileInfo;
    }

    /**
     * @return Returns the namespaced class and sub-directory fragment for the
     * associated configuration file.
     */
    public function __toString() {
        $escapedRootPath = '/' . \preg_replace('/\//', '\/', ROOT_PATH) . '/';
        return parent::__toString() . '::' . 'ROOT_PATH' . \preg_replace($escapedRootPath, '', $this->ConfigFileInfo->getPathname());
    }

}