<?php

/**
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

namespace libs\sprayfire\config;

/**
 * Will parse a JSON configuration file into an ImmutableStorage object with the
 * key/value pairs in that JSON object being converted to ImmutableStorage objects.
 *
 * Ultimately this allows for the reading of any JSON object using PHP's object
 * '->' or array '[]' access notations.  For a better example see the example
 * below.
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
class JsonConfig extends \libs\sprayfire\datastructs\ImmutableStorage implements \libs\sprayfire\config\Configuration {

    /**
     * Holds the object passed in the constructor holding the path to the configuration
     * file.
     *
     * @property SplFileInfo
     */
    private $ConfigFileInfo;

    /**
     * Will take an SplFileInfo object, return the complete path for the file held
     * by that object, convert the data in that file to a JSON array and then convert
     * the nested arrays into ImmutableStorageObjects providing a means to chain
     * together JSON keys through object or array access notation.
     *
     * Note, JSON objects passed to this object will be chainable arbirtrarily deep,
     * only limited to the stack depth allowed by `json_decode()`.
     *
     *
     *
     * @param $FileInfo SplFileInfo object
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function __construct(\SplFileInfo $FileInfo) {
        $this->ConfigFileInfo = $FileInfo;
        $data = $this->getDecodedJson();
        parent::__construct($data);
    }

    /**
     * Will retrieve the contents of the file passed in the constructor and
     * decode the contents if possible; if not possible to parse the contents
     * an exception will be thrown.
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    private function getDecodedJson() {
        $decodedJson = \json_decode($this->getFileContents(), true);
        $lastJsonError = \json_last_error();
        if ($lastJsonError !== JSON_ERROR_NONE || \is_null($decodedJson)) {
            \error_log('The last JSON error was ' . $lastJsonError);
            throw new \InvalidArgumentException('There was an error parsing the JSON configuration file passed.  Please see error log for more info.');
        }
        return $decodedJson;
    }

    /**
     * Will return the contents of the file associated with this configuration object.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function getFileContents() {
        if (!$this->ConfigFileInfo->isFile() && !$this->ConfigFileInfo->isLink()) {
            throw new \InvalidArgumentException('There is an error with the path to the configuration file.');
        }
        $fileInfo = \file_get_contents($this->ConfigFileInfo->getRealPath());
        return $fileInfo;
    }

    /**
     * @return string
     */
    public function __toString() {
        $escapedRootPath = '/' . \preg_replace('/\//', '\/', ROOT_PATH) . '/';
        return parent::__toString() . '::' . 'ROOT_PATH' . \preg_replace($escapedRootPath, '', $this->ConfigFileInfo->getPathname());
    }

}

// End JsonConfig
