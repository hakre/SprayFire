<?php

/**
 * @file
 * @brief Holds a class to ease the creation of absolute paths for framework and
 * application directories and files.
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

/**
 * @namespace libs.sprayfire.core
 * @brief Holds parts of the framework that are considered essential for SprayFire
 * to operate.
 *
 * @details
 * The classes and interfaces in this namespace are generally things that work
 * with the file system, load classes and otherwise make sure the framework has
 * a common foundation to work with.  Ultimately you can almost think of this as
 * a "utility" namespace, but ultimately goes into the heart of the framework.
 */
namespace libs\sprayfire\core {

    /**
     * @brief Used as a wrapper for what would otherwise be a series of global
     * constants, ultimately providing much ore; please see the details for more
     * info.
     *
     * @details
     * This class does much more than simply encapsulate a couple of global constants.
     * This is absolutely the preferred method to retrieve paths to directories
     * residing inside the /libs/sprayfire/ or /app/.  In addition, you can also
     * resolve the absolute paths for files this way.  Check out the examples for
     * more information.
     *
     * @example
     * <pre>
     *
     * // Below are some examples of how to use the class and what paths are returned
     * // For this example assume INSTALL_PATH is the primary directory for your
     * // app, being the directory holding /libs/sprayfire/
     *
     * libs.sprayfire.core.SprayFireDirectory::getFrameworkPath();
     *
     * // return value = INSTALL_PATH/libs/sprayfire
     *
     * libs.sprayfire.core.SprayFireDirectory::getAppPath();
     *
     * // return value = INSTALL_PATH/app
     *
     * libs.sprayfire.core.SprayFireDirectory::getFrameworkPathSubDirectory('config');
     *
     * // return value = INSTALL_PATH/libs/sprayfire/
     *
     * libs.sprayfire.core.SprayFireDirectory::getAppPathSubDirectory('config', 'config.json');
     *
     * // return value = INSTALL_PATH/app/config/config.json
     *
     * // Hopefully this demonstrates how to effectively use this class to easily
     * // create absolute paths to virtually any directory in the application.
     *
     * </pre>
     *
     */
    class SprayFireDirectory {

        /**
         * @brief Holds the ROOT_PATH that should be used by the class
         *
         * @details
         * Ultimately this property is used to facilitate testing, allowing us to
         * set the install path to a test directory structure.  Setting this value
         * will ultimately impact the directories that are returned.
         *
         * @property $installRoot
         */
        private static $installRoot = null;

        /**
         * @brief The absolute path to the root framework directory.
         *
         * @property $frameworkRoot
         */
        private static $frameworkRoot = null;

        /**
         *@brief The absolute path to the root app directory.
         *
         * @property $appRoot
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
         * @return The absolute path to the root app folder, with no trailing separator
         */
        public static function getAppPath() {
            return self::$appRoot;
        }

        /**
         * @brief Converts a variable list of arguments to an absolute path in the
         * ROOT_PATH/app/ directory.
         *
         * @return Absolute path to a sub directory or file in the root app folder, with no trailing separator
         */
        public static function getAppPathSubDirectory() {
            $subDirList = \func_get_args();
            if (\count($subDirList) === 0) {
                return self::getAppPath();
            }

            // this is here to support passing an array as the only argument
            if (\is_array($subDirList[0])) {
                $subDirList = $subDirList[0];
            }
            $subDirPath = self::getSubDirectoryPath($subDirList);
            return self::getAppPath() . DS . $subDirPath;
        }

        /**
         * @return Absolute path to the root framework folder, with no trailing separator
         */
        public static function getFrameworkPath() {
            return self::$frameworkRoot;
        }

        /**
         * @brief Converts a variable list of arguments to an absolute path in the
         * ROOT_PATH/libs/sprayfire/ directory
         *
         * @return Absolute path to a sub directory or file in the root framework folder, with no trailing separator
         */
        public static function getFrameworkPathSubDirectory() {
            $subDirList = \func_get_args();
            if (\count($subDirList) === 0) {
                return self::getFrameworkPath();
            }

            // this is here to support passing an array as the only argument
            if (\is_array($subDirList[0])) {
                $subDirList = $subDirList[0];
            }
            $subDirPath = self::getSubDirectoryPath($subDirList);
            $fullPath = self::getFrameworkPath() . DS . $subDirPath;
            return $fullPath;
        }

        /**
         * @brief Converts an array of strings into a sub-directory fragment with
         * no trailing separator.
         *
         * @details
         * This is implemented to make the creation of framework and app sub-directories
         * as generic as possible.
         *
         * @param $subDirList array
         * @return A sub directory fragment with no trailing separator
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

}

// End libs.sprayfire.core