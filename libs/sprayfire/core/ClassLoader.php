<?php

/**
 * @file
 * @brief Holds a class used as the framework autoloader, converting a namespaced
 * class to an absolute directory.
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
 * @copyright Copyright (c) 2011, Charles Sprayberry
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
     * @brief Responsible for including namespaced framework and application classes,
     * assuming they abide to the rules set forth by the framework.
     */
    class ClassLoader extends \libs\sprayfire\core\CoreObject {

        /**
         * @brief Adds the class's autoloader function to the autoload register.
         *
         * @codeCoverageIgnore
         */
        public function setAutoLoader() {
            \spl_autoload_register(array($this, 'loadClass'));
        }

        /**
         * @brief Include the class based on the fully namespaced \a $className passed.
         *
         * @param $className The namespaced class to load
         */
        private function loadClass($className) {
            $convertedPath = $this->convertNamespacedClassToDirectoryPath($className);
            if (\file_exists($convertedPath)) {
                include $convertedPath;
            }
        }

        /**
         * @brief Converts the PHP namespace separator to the appropriate directory
         * separator.
         *
         * @param $className Namespaced name of the class to load
         * @return The complete path to the class
         */
        private function convertNamespacedClassToDirectoryPath($className) {
            $convertedPath = ROOT_PATH . DS;
            $convertedPath .= \str_replace('\\', DS, $className);
            $convertedPath .= '.php';
            return $convertedPath;
        }

    }

    // End ClassLoader

}

// libs.sprayfire.core