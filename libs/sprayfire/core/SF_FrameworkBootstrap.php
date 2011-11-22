<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * The framework's default bootstrap, is primarily responsible for writing the default
 * values in the CoreConfiguration object and running each bootstrap object located
 * in the app/bootstrap folder.
 */
class SF_FrameworkBootstrap implements SF_IsBootstrapper {

    /**
     * The framework's core configuration object, will be passed to each bootstrap
     * object ran.
     *
     * @var SF_CoreConfiguration
     */
    private $CoreConfiguration;

    /**
     * @param SF_IsConfigurationStorage $CoreConfiguration
     */
    public function __construct(SF_IsConfigurationStorage $CoreConfiguration) {
        $this->CoreConfiguration = $CoreConfiguration;
    }

    /**
     * Will write the default configuration values and then run the appropriate
     * app bootstrap objects.
     */
    public function runBootstrap() {
        $this->writeCoreConfiguration();
        $this->runAppBootstrap();
    }

    /**
     *
     */
    private function writeCoreConfiguration() {
        $this->CoreConfiguration->write('default_controller', 'pages');
        $this->CoreConfiguration->write('default_action', 'index');
        $this->CoreConfiguration->write('default_controller_interface', 'SF_IsController');
        $this->CoreConfiguration->write('database_config', 'DatabaseConfig');
        $this->CoreConfiguration->write('error_controller', 'error');
        $this->CoreConfiguration->write('error_action', 'index');
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
        $bootstrapPath = APP_PATH . DS . 'bootstrap';
        $directoryHandle = opendir($bootstrapPath);
        $files = array();
        $restrictedFiles = array('.', '..', '.DS_Store');
        if (!$directoryHandle) {
            return $files;
        }
        while (false !== ($file = readdir($directoryHandle))) {
            if (!is_dir($file)) {
                if (!in_array($file, $restrictedFiles)) {
                    $files[] = $file;
                }
            }
        }
        return $files;
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
            $className = preg_replace($regexPattern, '', $file);
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
            $isValidBootstrapper = $this->classImplementsBootstrapperInterface($class);
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
     * @param string $className
     * @return boolean
     */
    private function classImplementsBootstrapperInterface($className) {
        try {
            $nameOfBootstapperInterface = 'SF_IsBootstrapper';
            $ReflectedClass = new ReflectionClass($className);
            if ($ReflectedClass->implementsInterface($nameOfBootstapperInterface)) {
                return true;
            }
        } catch (ReflectionException $ReflectionException) {
            // no need to do anything here as the final return of false will
            // handle this exception
            error_log($ReflectionException->getMessage());
        }
        return false;
    }

    /**
     * Will turn a list of bootstrap files into bootstrap objects, raady to have
     * bootstrap methods invoked on them.
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
        if (empty($objects)) {
            return;
        }
        foreach ($objects as $object) {
            $object->runBootstrap();
        }
    }

}

// End SF_FrameworkBootstrap
