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
 * @version 0.10b
 * @since 0.10b
 */

namespace libs\sprayfire\core;

/**
 * This class is responsible for including framework and application classes
 * using PHP's autoload mechanism.
 */
class ClassLoader extends CoreObject {

    /**
     * Adds the class's autoloader function to the autoload register.
     *
     * @codeCoverageIgnore
     */
    public function setAutoLoader() {
        \spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Will include the necessary class based on the fully namespaced class passed.
     *
     * @param string $className
     */
    private function loadClass($className) {
        $convertedPath = $this->convertNamespacedClassToDirectoryPath($className);
        if (file_exists($convertedPath)) {
            include $convertedPath;
        }
    }

    /**
     * Will convert the `\` in namespaces to the appropriate directory separator
     * and then determine the complete, absolute path to the requested class.
     *
     * @param string $className
     * @return string
     */
    private function convertNamespacedClassToDirectoryPath($className) {
        $convertedPath = ROOT_PATH . DS;
        $convertedPath .= str_replace('\\', DS, $className);
        $convertedPath .= '.php';
        return $convertedPath;
    }

}

// End ClassLoader
