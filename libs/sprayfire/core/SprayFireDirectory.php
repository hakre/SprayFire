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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\core;

/**
 * This class is used as a wrapper for what would otherwise be a constant in the
 * framework.
 *
 * Ultimately though the methods in this class provide a very good functionality
 * that makes it easy to easily get a framework or application directory anywhere
 * in the framework.
 */
class SprayFireDirectory implements \libs\sprayfire\interfaces\FrameworkPaths {

    /**
     * @var string
     */
    private static $installRoot = null;

    /**
     * @var string
     */
    private static $frameworkRoot = null;

    /**
     * @var string
     */
    private static $appRoot = null;

    /**
     * @return string
     */
    public static function getAppPath() {
        return self::$appRoot;
    }

    /**
     * The $subDirectory passed should be separated by directory separators after
     * the first directory.
     *
     * @param string $subDirectory
     * @return string
     */
    public static function getAppPathSubDirectory($subDirectory) {
        return self::$appRoot . DS . $subDirectory;
    }

    /**
     * @return string
     */
    public static function getFrameworkPath() {
        return self::$frameworkRoot;
    }

    /**
     * The $subDirectory passed should be separated by directory separators after
     * the first directory.
     *
     * @param string $subDirectory
     * @return string
     */
    public static function getFrameworkPathSubDirectory($subDirectory) {
        return self::$frameworkRoot . DS . $subDirectory;
    }

    /**
     * @param string $rootInstallationPath
     */
    public static function setRootInstallationPath($rootInstallationPath) {
        self::$installRoot = $rootInstallationPath;
        self::$frameworkRoot = self::$installRoot . DS . 'libs' . DS . 'sprayfire';
        self::$appRoot = self::$installRoot . DS . 'app';
    }
}

// End SprayFireDirectory
