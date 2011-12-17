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

namespace libs\sprayfire\bootstrap;

/**
 * The framework's default bootstrap, is primarily responsible for writing the default
 * values in the CoreConfiguration object and running each bootstrap object located
 * in the app/bootstrap folder.
 */
class FrameworkBootstrap extends \libs\sprayfire\core\CoreObject implements \libs\sprayfire\bootstrap\Bootstrapper {

    /**
     * The framework's core configuration object, will be passed to each bootstrap
     * object ran.
     *
     * @var Configuration
     */
    private $FrameworkConfiguration;

    /**
     * @param Configuration $FrameworkConfiguration
     */
    public function __construct(\libs\sprayfire\config\Configuration $FrameworkConfiguration) {
        $this->FrameworkConfiguration = $FrameworkConfiguration;
    }

    /**
     * Will write the default configuration values and then run the appropriate
     * app bootstrap objects.
     */
    public function runBootstrap() {
        $this->runAppBootstrap();
        $this->runPostAppBootstrap();
    }

    /**
     * Will get a list of app boostrap objects and invoke the runBootstrap() method
     * on the list of valid objects.
     */
    private function runAppBootstrap() {
        $bootstrapObjects = $this->getAppBootstrapObjects();
        $this->runAllBootstraps($bootstrapObjects);
    }

    /**
     * Will return an array of bootstrap objects that properly implement the
     * framework bootstrapper interface.
     *
     * If no files are found in the app/bootstrap directory or the objects in that
     * directory do not implement the proper interface the array of objects may
     * be an empty array.  Either way, an array will be returned.
     *
     * @return array
     */
    private function getAppBootstrapObjects() {
        $bootstrapFiles = $this->getListOfBootstrapFiles();
        if (empty($bootstrapFiles)) {
            return $bootstrapFiles;
        }
        $this->removeFileExtension($bootstrapFiles);
        $this->removeInvalidBootstrappers($bootstrapFiles);
        $bootstrapObjects = $this->createBootstrapperObjects($bootstrapFiles);
        return $bootstrapObjects;
    }

    /**
     * Returns the list of files stored in the app/bootstrap directory, or an empty
     * array if there are no files stored in the directory.
     *
     * @return array
     */
    private function getListOfBootstrapFiles() {
        $bootstrapPath = SprayFireDirectory::getAppPathSubDirectory('bootstrap');
        $directoryHandle = \opendir($bootstrapPath);
        $files = array();
        if (!$directoryHandle) {
            \error_log('Error opening the app/bootstrap files in ' . $bootstrapPath);
            return $files;
        }
        $restrictedFiles = array('.', '..', '.DS_Store');
        $appBootstrapNamespace = $this->getAppBootstrapNamespace();
        $appBootstrapNamespace .= '\\';
        while (false !== ($file = \readdir($directoryHandle))) {
            if (!\is_dir($file)) {
                if (!\in_array($file, $restrictedFiles)) {
                    $files[] = $appBootstrapNamespace . $file;
                }
            }
        }
        return $files;
    }

    /**
     * This method will return the proper namespace that should be implemented by
     * bootstrap objects.
     *
     * @return string
     * @internal This somewhat hacky method is used to help facilitate unit testing
     * on the bootstrap object.  Damn you global scope, damn you!
     */
    private function getAppBootstrapNamespace() {
        $bootstrapDir = SprayFireDirectory::getAppPathSubDirectory('bootstrap');

        // we are doing this str_replace to prevent collisions with regex and /
        $bootstrapDir = \str_replace('/', '_', $bootstrapDir);
        $regexReadyRootPath = \str_replace('/', '_', ROOT_PATH);

        $regexPattern = '/' . $regexReadyRootPath . '_/';
        $bootstrapDir = \preg_replace($regexPattern, '', $bootstrapDir);

        // now we just want to change the underscores to the namespace separator
        $bootstrapNamespace = \str_replace('_', '\\', $bootstrapDir);

        return $bootstrapNamespace;
    }

    /**
     * Will take an array by reference and remove the '.php' file extensions on
     * the end of those file names.
     *
     * @param array $files
     */
    private function removeFileExtension(array &$files) {
        $regexPattern = '/(.php)$/';
        foreach ($files as $key => $file) {
            $className = \preg_replace($regexPattern, '', $file);
            $files[$key] = $className;
        }
    }

    /**
     * Will remove the name of any class in the array that does not properly implement
     * the framework's bootstrapping interface.
     *
     * @param array $classes
     */
    private function removeInvalidBootstrappers(array &$classes) {
        $validClasses = array();
        foreach ($classes as $key => $class) {
            $isValidBootstrapper = $this->implementsBootstrapperInterface($class);
            if ($isValidBootstrapper) {
                $validClasses[$key] = $class;
            }
        }
        $classes = $validClasses;
    }

    /**
     * Will determine whether or not the given class name implements the correct
     * interface.
     *
     * Note that since we are using Reflection this also checks if the class can
     * be instantiated.
     *
     * @param string $className
     * @return boolean
     */
    private function implementsBootstrapperInterface($className) {
        try {
            $bootstapperInterface = '\\libs\\sprayfire\\bootstrap\\Bootstrapper';
            $ReflectedClass = new \ReflectionClass($className);
            if ($ReflectedClass->implementsInterface($bootstapperInterface)) {
                return true;
            }
        } catch (\ReflectionException $ReflectionException) {
            // no need to do anything here as the final return of false will
            // handle this exception
            \error_log($ReflectionException->getMessage());
        }
        return false;
    }

    /**
     * Will turn a list of bootstrap files into bootstrap objects, raady to have
     * bootstrap methods invoked on them.
     *
     * Since we already know the bootstrapper objects implement the correct interface
     * we simply need to creat an array of objects, being sure to pass in the configuration
     * object.
     *
     * @param array $classes
     * @return array
     */
    private function createBootstrapperObjects(array $classes) {
        $objects = array();
        foreach ($classes as $class) {
            $objects[] = new $class($this->CoreConfiguration);
        }
        return $objects;
    }

    /**
     * An array of bootstrapper objects that should have the runBootstrap() methods
     * invoked on them.
     *
     * @param array $objects
     */
    private function runAllBootstraps(array $objects) {
        foreach ($objects as $object) {
            $object->runBootstrap();
        }
    }

    /**
     * Will set constants and ini values based on the values written in CoreConfiguration
     * after all app bootstraps have been given a chance to overwrite those values.
     */
    private function runPostAppBootstrap() {
        $this->setDebugMode();
    }

}

// End FrameworkBootstrap
