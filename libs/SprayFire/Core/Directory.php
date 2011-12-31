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
class Directory extends \SprayFire\Core\CoreObject implements \SprayFire\Core\PathGenerator {

    /**
     * @brief The root path holding the primary directories used by the app and
     * fraework.
     *
     * @property $installPath
     */
    protected $installPath;

    /**
     * @brief The root path holding framework and third-party classes used by the
     * app and framework.
     *
     * @property $libsPath
     */
    protected $libsPath;

    /**
     * @brief The root path holding app classes
     *
     * @property $appPath
     */
    protected $appPath;

    protected $configPath;

    /**
     * @brief The root path holding log files that are written to by the framework
     * or app
     *
     * @property $logsPath
     */
    protected $logsPath;

    /**
     * @brief The root path holding files that are accessible via the web
     *
     * @property $webPath
     */
    protected $webPath;

    /**
     * @param $path The path that the app and framework are installed in
     */
    public function setInstallPath($path) {
        $this->installPath = $path;
    }

    /**
     * @param $path The path holding framework and third-party libs
     */
    public function setLibsPath($path) {
        $this->libsPath = $path;
    }

    /**
     * @param $path The path holding app classes and libs
     */
    public function setAppPath($path) {
        $this->appPath = $path;
    }

    public function setConfigPath($path) {
        $this->configPath = $path;
    }

    /**
     * @param $path The path holding log files written to by the framework and app
     */
    public function setLogsPath($path) {
        $this->logsPath = $path;
    }

    /**
     * @param $path the path holding web accessible files
     */
    public function setWebPath($path) {
        $this->webPath = $path;
    }

    /**
     * @return The path holding the path the app and framework are installed in
     */
    public function getInstallPath($subDir = array()) {
        return $this->generateFullPath('installPath', \func_get_args());
    }

    /**
     * @return Absolute path to libs dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public function getLibsPath($subDir = array()) {
        return $this->generateFullPath('libsPath', \func_get_args());
    }

    /**
     * @return Absolute path to app dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public function getAppPath($subDir = array()) {
        return $this->generateFullPath('appPath', \func_get_args());
    }

    /**
     * @param $subDir An array of variables
     * @return A path to the configuration file
     */
    public function getConfigPath($subDir = array()) {
        return $this->generateFullPath('configPath', \func_get_args());
    }

    /**
     * @return Absolute path to logs dir, with sub-directories appended if applicable,
     *         and no trailing separator
     */
    public function getLogsPath($subDir = array()) {
        return $this->generateFullPath('logsPath', \func_get_args());
    }

    /**
     * @return Absolute path to web dir, with sub-directories appended if applicable,
     *         and no trailing whitespace
     */
    public function getWebPath($subDir = array()) {
        return $this->generateFullPath('webPath', \func_get_args());
    }

    /**
     * @return A relative path to the framework web-root or a subdirectory suitable
     *         for use as image, stylesheet and script links
     */
    public function getUrlPath($subDir = array()) {
        $webRoot = \basename($this->getWebPath());
        $installRoot = \basename($this->getInstallPath());
        $urlPath = '/' . $installRoot . '/' . $webRoot;
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return $urlPath;
        }
        return $urlPath . '/' . $this->generateSubDirectoryPath($subDir);
    }

    /**
     * @param $property The class property to use for the primary path
     * @param $subDir A list of possible sub directories to add to primary path
     * @return Absolute path for the given class \a $property and \a $subDir
     */
    protected function generateFullPath($property, array $subDir) {
        if (\count($subDir) === 0) {
            return $this->$property;
        }
        return $this->$property . '/' . $this->generateSubDirectoryPath($subDir);
    }

    /**
     * @param $subDir
     * @return A sub directory path, with n otrailing separator
     */
    protected function generateSubDirectoryPath(array $subDir) {
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