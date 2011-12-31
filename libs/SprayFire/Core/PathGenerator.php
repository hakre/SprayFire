<?php

/**
 * @file
 * @brief Holds an interface used to get absolute and relative paths to directories
 * and files used by the framework and/or app.
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
 * @brief An interface to be implemented by objects that are responsible for creating
 * absolute paths to directories and files on the target file system.
 */
interface PathGenerator {

    /**
     * @brief Should return the root path that the app and framework is installed
     * in; should also accept either an array or a variable number of arguments
     * to be interpreted as the sub directories and/or files to append to the install
     * path.
     *
     * @param $subDir
     */
    public function getInstallPath($subDir = array());

    /**
     * @brief Should return the libs path that SprayFire and third-party libs are
     * installed in; should also accept either an array or a variable number of
     * arguments to be interpreted as the sub directories and/or files to append
     * to the libs path.
     *
     * @param $subDir
     */
    public function getLibsPath($subDir = array());

    /**
     * @brief Should return the app path that the app is installed in should also
     * accept either an array or a variable number of arguments to be interpreted
     * as the sub directories and/or files to append to the app path.
     *
     * @param $subDir
     */
    public function getAppPath($subDir = array());

    /**
     * @brief Should return the logs path that error and stats logs should be stored
     * in; should also accept either an array or a variable number of arguments
     * to be interpreted as the sub directories and/or files to append to the logs
     * path.
     *
     * @param $subDir
     */
    public function getLogsPath($subDir = array());

    /**
     * @brief Should return the config path that the configuration JSON and XML
     * files are stored in; should also accept either an array or a variable number
     * of arguments to be interpreted as the sub directories and/or files to append
     * to the config path.
     *
     * @param $subDir
     */
    public function getConfigPath($subDir = array());

    /**
     * @brief Should return the web path that the web accessible files are stored
     * in; should also accept either an array or a variable number of arguments
     * to be interpreted as the sub directories and/or files to append to the web
     * path.
     *
     * @param $subDir
     */
    public function getWebPath($subDir = array());

    /**
     * @brief Should return a relative path suitable for use in HTML templates;
     * should also accept either an array or a variable number of arguments to be
     * interpreted as the sub directories and/or files to append to the web $path.
     *
     * @details
     * Ultimatley this means that this function will need to return the basename
     * of the install path appended to the web path without the root directory
     * attached.
     *
     * @param $subDir
     */
    public function getUrlPath($subDir = array());

}