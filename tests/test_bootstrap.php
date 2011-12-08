<?php

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

     include FRAMEWORK_PATH . DS . 'interfaces' . DS . 'Object.php';
     include FRAMEWORK_PATH . DS . 'core' . DS . 'CoreObject.php';
     include FRAMEWORK_PATH . DS . 'core' . DS . 'ClassLoader.php';

     $ClassLoader = new ClassLoader();
     $ClassLoader->setAutoLoader();