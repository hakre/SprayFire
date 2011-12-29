<?php

/**
 * @file
 * @brief Holds a class used as the framework autoloader, converting a namespaced
 * class to an absolute directory.
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
 * @brief Responsible for including namespaced framework and application classes,
 * assuming they abide to the rules set forth by the framework.
 *
 * @details
 *
 */
class ClassLoader {

    /**
     * @brief An array holding a top-level namespace as the key and the complete
     * root path for that namespace as the value.
     *
     *
     *
     * @property $namespaceMap
     */
    protected $namespaceMap = array();

    /**
     * @brief Allows access to set a given namespace to a given directory.
     *
     * @details
     * The directory should be assigned in such a way that the following occurs:
     *
     * <pre>
     * The class:
     *
     * Top.Level.ClassName
     *
     * The directory for that class:
     *
     * /install_path/app/Top/Level/ClassName.php
     *
     * The proper key and value for this namspace and directory would look like:
     *
     * $namespaceMap['Top'] = '/install_path/app';
     *
     * Thus when you attempt to instantiate the class like so:
     *
     * $Class = new Top.Level.ClassName();
     *
     * The class autoloader will convert the namespace to a directory and then
     * append that directory to the value stored by the 'Top' key.
     * </pre>
     *
     * @param $topLevelNamespace A string representing a top level namespace
     * @param $dir The complete path to the directory holding the top level namespace
     */
    public function registerNamespaceDirectory($topLevelNamespace, $dir) {
        if (!empty($topLevelNamespace) && !empty($dir)) {
            $this->namespaceMap[$topLevelNamespace] = $dir;
        }
    }

    /**
     * @brief Include the class based on the fully namespaced \a $className passed.
     *
     * @param $className The namespaced class to load
     */
    public function loadClass($className) {
        $namespace = $this->getTopLevelNamespace($className);
        $path = $this->getDirectoryForTopLevelNamespace($namespace) . '/';
        $path .= $this->convertNamespacedClassToFilePath($className);
        if (\file_exists($path)) {
            include $path;
        }
    }

    /**
     * @brief Will return the top-level namespace for a class, given it has a namespace
     *
     * @param $className Fully namespaced name of the class
     * @return mixed
     */
    protected function getTopLevelNamespace($className) {
        $className = \ltrim($className, '\\ ');
        $namespaces = \explode('\\', $className);
        if (\count($namespaces) > 0) {
            return $namespaces[0];
        }
        return NULL;
    }

    /**
     * @brief Will check to see if the \a $namespace has a directory mapped to it,
     * if not we assume that it is in the app path.
     *
     * @param $namespace A top-level namespace that may exist in \a $namespaceMap
     * @return string
     * @see SprayFire.Core.Directory
     */
    protected function getDirectoryForTopLevelNamespace($namespace) {
        if (isset($namespace) && \array_key_exists($namespace, $this->namespaceMap)) {
            return $this->namespaceMap[$namespace];
        }
        return \SprayFire\Core\Directory::getAppPath();
    }

    /**
     * @brief Converts the PHP namespace separator to the appropriate directory
     * separator.
     *
     * @param $className Namespaced name of the class to load
     * @return The complete path to the class
     */
    protected function convertNamespacedClassToFilePath($className) {
        $path = \str_replace('\\', '/', $className);
        $path .= '.php';
        return $path;
    }

}