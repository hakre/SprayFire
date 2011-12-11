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
 *
 * @uses \libs\sprayfire\interfaces\FrameworkPaths
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
     * @param string $rootInstallationPath
     */
    public static function setRootInstallationPath($rootInstallationPath) {
        self::$installRoot = $rootInstallationPath;
        self::$frameworkRoot = self::$installRoot . DS . 'libs' . DS . 'sprayfire';
        self::$appRoot = self::$installRoot . DS . 'app';
    }

    /**
     * @return string
     */
    public static function getAppPath() {
        return self::$appRoot;
    }

    /**
     * Converts a variable list of strings into a directory separated path that
     * will be interpreted to be a sub directory in the ROOT_PATH/app/ dir
     *
     * @param string $subDirectory
     * @return string
     */
    public static function getAppPathSubDirectory() {
        $subDirList = \func_get_args();
        if (\count($subDirList) === 0)
        $subDirPath = self::getSubDirectoryPath($subDirList);
        return self::getAppPath() . DS . $subDirPath;
    }

    /**
     * @return string
     */
    public static function getFrameworkPath() {
        return self::$frameworkRoot;
    }

    /**
     * Converts a variable list of strings into a directory separated path that
     * will be interpreted as a sub directory in the ROOT_PATH/libs/sprayfire dir
     *
     * @param string $subDirectory
     * @return string
     */
    public static function getFrameworkPathSubDirectory() {
        $subDirList = \func_get_args();
        if (\count($subDirList) === 0) {
            return self::getFrameworkPath();
        }
        $subDirPath = self::getSubDirectoryPath($subDirList);
        $fullPath = self::getFrameworkPath() . DS . $subDirPath;
        return $fullPath;
    }

    /**
     * @param array $subDirList
     * @return string
     */
    private static function getSubDirectoryPath(array $subDirList) {
        $subDirPath = '';
        foreach ($subDirList as $subDir) {
            $subDirPath .= \trim($subDir) . DS;
        }
        $subDirPath = \rtrim($subDirPath, DS);
        return $subDirPath;
    }

}

// End SprayFireDirectory
