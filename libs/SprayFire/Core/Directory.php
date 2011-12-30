<?php

/**
 * @file
 * @brief Holds a class to ease the creation of absolute paths for directories and
 * files used by the app and framework.
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
 * @brief A wrapper used as a utility class to easily create absolute paths for
 * various directories in the app and framework.
 *
 * @details
 * This class does much more than simply encapsulate a couple of global constants.
 * This is absolutely the preferred method to retrieve paths to directories
 * residing inside installation path directories.  In addition, you can also
 * resolve the absolute paths for files this way.
 */
class Directory {

    /**
     * @brief The root path holding the primary directories used by the app and
     * fraework.
     *
     * @property $installPath
     */
    protected static $installPath;

    /**
     * @brief The root path holding framework and third-party classes used by the
     * app and framework.
     *
     * @property $libsPath
     */
    protected static $libsPath;

    /**
     * @brief The root path holding app classes
     *
     * @property $appPath
     */
    protected static $appPath;

    /**
     * @brief The root path holding log files that are written to by the framework
     * or app
     *
     * @property $logsPath
     */
    protected static $logsPath;

    /**
     * @brief The root path holding files that are accessible via the web
     *
     * @property $webPath
     */
    protected static $webPath;

    /**
     * @param $path The path that the app and framework are installed in
     */
    public static function setInstallPath($path) {
        self::$installPath = $path;
    }

    /**
     * @param $path The path holding framework and third-party libs
     */
    public static function setLibsPath($path) {
        self::$libsPath = $path;
    }

    /**
     * @param $path The path holding app classes and libs
     */
    public static function setAppPath($path) {
        self::$appPath= $path;
    }

    /**
     * @param $path The path holding log files written to by the framework and app
     */
    public static function setLogsPath($path) {
        self::$logsPath = $path;
    }

    /**
     * @param $path the path holding web accessible files
     */
    public static function setWebPath($path) {
        self::$webPath = $path;
    }

    /**
     * @return The path holding the path the app and framework are installed in
     */
    public static function getInstallPath() {
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return self::$installPath;
        }
        return self::$installPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @return Absolute path to libs dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public static function getLibsPath() {
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return self::$libsPath;
        }
        return self::$libsPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @return Absolute path to app dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public static function getAppPath() {
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return self::$appPath;
        }
        return self::$appPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @return Absolute path to logs dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public static function getLogsPath() {
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return self::$logsPath;
        }
        return self::$logsPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @return Absolute path to web dir, with sub-directories appended if applicable,
     *         and no trailing whitespace
     */
    public static function getWebPath() {
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return self::$webPath;
        }
        return self::$webPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @return A relative path to the framework web-root or a subdirectory suitable
     *         for use as image, stylesheet and script links
     */
    public static function getUrlPath() {
        $webRoot = \basename(self::getWebPath());
        $installRoot = \basename(self::getInstallPath());
        $urlPath = $installRoot . '/' . $webRoot;
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return $urlPath;
        }
        return '/' . $urlPath . '/' . self::generateSubDirectoryPath($subDir);
    }

    /**
     * @param $subDir
     * @return A sub directory path, with n otrailing separator
     */
    protected static function generateSubDirectoryPath(array $subDir) {
        $subDirPath = '';
        if (\is_array($subDir[0])) {
            $subDir = $subDir[0];
        }
        foreach ($subDir as $dir) {
            $subDirPath .= \trim($dir) . '/';
        }
        $subDirPath = \rtrim($subDirPath, '/');
        return $subDirPath;
    }

}