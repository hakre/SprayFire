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
    * @var string
    */
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    /**
     * @var string
     */
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)));

    /**
     * @var string
     */
    defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', ROOT_PATH . DS . 'libs' . DS . 'sprayfire');

    /**
     * @var string
     */
    defined('APP_PATH') or define('APP_PATH', ROOT_PATH . DS . 'app');

    include FRAMEWORK_PATH . DS . 'core' . DS . 'CoreObject.php';
    include FRAMEWORK_PATH . DS . 'core' . DS . 'ClassLoader.php';

    $ClassLoader = new ClassLoader();
    $ClassLoader->setAutoloader();

    $CoreConfiguration = new CoreConfiguration();
    $FrameworkBootstrapper = new FrameworkBootstrap($CoreConfiguration);

    $FrameworkBootstrapper->runBootstrap();

    echo 'Ran bootstrap!';

