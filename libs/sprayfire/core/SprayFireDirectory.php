<?php

/**
 * @file
 * @brief
 *
 * @details
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

namespace libs\sprayfire\core;

/**
 * This class is used as a wrapper for what would otherwise be a constant in the
 * framework.
 *
 * Ultimately though the methods in this class provide a very good functionality
 * that makes it easy to easily get a framework or application directory anywhere
 * in the framework.
 */
class SprayFireDirectory implements \libs\sprayfire\core\FrameworkPaths {

    /**
     * @property string
     */
    private static $installRoot = null;

    /**
     * @property string
     */
    private static $frameworkRoot = null;

    /**
     * @property string
     */
    private static $appRoot = null;

    /**
     * @param $rootInstallationPath string
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
     * @return string
     */
    public static function getAppPathSubDirectory() {
        $subDirList = \func_get_args();
        if (\count($subDirList) === 0) {
            return self::getAppPath();
        }
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
     * @param $subDirList array
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
