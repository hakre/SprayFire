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
 * This class is responsible for including framework and application classes
 * using PHP's autoload mechanism.
 */
class ClassLoader extends CoreObject {

    /**
     * An array that lists the framework classes and the directories associated
     * with the class, the key should be the class name and the value stored should
     * be the complete file path for that class.
     *
     * @var array
     */
    private $knownClassDirectory = array();

    /**
     * Will create an array of known classes and the directories associated with
     * them and setup the include path for all known app folders.
     */
    public function __construct() {
        $this->createKnownDirectory();
        $this->setIncludePath();
    }

    /**
     * Adds the class's autoloader function to the autoload register.
     */
    public function setAutoLoader() {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Creates the values for the known classes and the directories associated
     * with them.
     */
    private function createKnownDirectory() {
        $this->setCoreDirectory();
        $this->setInterfaceDirectory();
        $this->setExceptionDirectory();
    }

    /**
     * Will assign the classes that are found in the FRAMEWORK_PATH/core/
     * directory.
     */
    private function setCoreDirectory() {
        $coreDir = FRAMEWORK_PATH . DS . 'core' . DS;
        $this->knownClassDirectory['BaseConfig'] = $coreDir . 'BaseConfig.php';
        $this->knownClassDirectory['ComponentFactory'] = $coreDir . 'ComponentFactory.php';
        $this->knownClassDirectory['BaseController'] = $coreDir . 'BaseController.php';
        $this->knownClassDirectory['ControllerFactory'] = $coreDir . 'ControllerFactory.php';
        $this->knownClassDirectory['CoreConfiguration'] = $coreDir . 'CoreConfiguration.php';
        $this->knownClassDirectory['CoreObject'] = $coreDir . 'CoreObject.php';
        $this->knownClassDirectory['DataSourceStore'] = $coreDir . 'DataSourceStore.php';
        $this->knownClassDirectory['FrameworkBootstrap'] = $coreDir . 'FrameworkBootstrap.php';
        $this->knownClassDirectory['RequestParser'] = $coreDir . 'RequestParser.php';
    }

    /**
     * Will assign the classes that are found in the FRAMEWORK_PATH/interfaces/
     * directory.
     */
    private function setInterfaceDirectory() {
        $interfacesDir = FRAMEWORK_PATH . DS . 'interfaces'. DS;
        $this->knownClassDirectory['Bootstrapper'] = $interfacesDir . 'Bootstrapper.php';
        $this->knownClassDirectory['Configuration'] = $interfacesDir . 'Configuration.php';
        $this->knownClassDirectory['Controller'] = $interfacesDir . 'Controller.php';
        $this->knownClassDirectory['DataSource'] = $interfacesDir. 'DataSource.php';
        $this->knownClassDirectory['Model'] = $interfacesDir . 'Model.php';
        $this->knownClassDirectory['Overloadable'] = $interfacesDir . 'Overloadable.php';
        $this->knownClassDirectory['PersistenceManager'] = $interfacesDir . 'PersistenceManager.php';
        $this->knownClassDirectory['QueryConditions'] = $interfacesDir . 'QueryConditions.php';
        $this->knownClassDirectory['QueryData'] = $interfacesDir . 'QueryData.php';
        $this->knownClassDirectory['QueryFields'] = $interfacesDir . 'QueryFields.php';
        $this->knownClassDirectory['QueryResult'] = $interfacesDir . 'QueryResult.php';
        $this->knownClassDirectory['Responder'] = $interfacesDir . 'Responder.php';
    }

    /**
     * Will assign the classes that are found in the FRAMEWORK_PATH/exceptions/
     * directory.
     */
    private function setExceptionDirectory() {
        $exceptionDir = FRAMEWORK_PATH . DS . 'exceptions' . DS;
        $this->knownClassDirectory['InvalidConfigurationException'] = $exceptionDir . 'InvalidConfigurationException.php';
        $this->knownClassDirectory['InvalidDataSourceException'] = $exceptionDir . 'InvalidDataSourceException.php';
        $this->knownClassDirectory['InvalidTemplateException'] = $exceptionDir . 'InvalidTemplateException.php';
        $this->knownClassDirectory['PhpFailureException'] = $exceptionDir . 'PhpFailureException.php';
        $this->knownClassDirectory['UnkownClassException'] = $exceptionDir . 'UnknownClassException.php';

    }

    /**
     * Will ensure the include path has all necessary folders so that classes
     * are included in the include path search.
     */
    private function setIncludePath() {
        $originalPath = get_include_path();
        $frameworkPath = FRAMEWORK_PATH;
        $appBootstrapPath = APP_PATH . DS . 'bootstrap';
        $appConfigPath = APP_PATH . DS . 'config';
        $appControllerPath = APP_PATH . DS . 'controllers';
        $appModelPath = APP_PATH . DS . 'models';
        $appRespondersPath = APP_PATH . DS . 'responders';

        $allPaths = $originalPath;
        $allPaths .= PATH_SEPARATOR . $frameworkPath;
        $allPaths .= PATH_SEPARATOR . $appBootstrapPath;
        $allPaths .= PATH_SEPARATOR . $appConfigPath;
        $allPaths .= PATH_SEPARATOR . $appControllerPath;
        $allPaths .= PATH_SEPARATOR . $appModelPath;
        $allPaths .= PATH_SEPARATOR . $appRespondersPath;

        set_include_path($allPaths);
    }

    /**
     * Will include the necessary class based on whether or not the directory
     * for the class is known.
     *
     * @param string $className
     */
    private function loadClass($className) {
        $listOfClasses = array_keys($this->knownClassDirectory);
        if (in_array($className, $listOfClasses)) {
            $classPath = $this->knownClassDirectory[$className];
        } else {
            $classPath = $className . '.php';
        }
        include $classPath;
    }

}

// End ClassLoader
