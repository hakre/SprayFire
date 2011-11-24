<?php

    // This testing mechanism is rather crude right now.  We basically have no
    // real functionality with the framework at this point so we will be doing some
    // rudimentary displaying of test results.  As the framework evolves this is
    // expected to change.

    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)));
    defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', ROOT_PATH . DS . 'libs' . DS . 'sprayfire');
    defined('APP_PATH') or define('APP_PATH', ROOT_PATH . DS . 'app');

    require_once 'PHPUnit/Autoload.php';

    include '../tests/SF_RequestParserTest.php';
    $RequestParserTest = new SF_RequestParserTest();
    $RequestParserTest->testRequestParser();

    echo '<br />';
    echo '<br />';

    include '../tests/SF_CoreConfigurationTest.php';
    $CoreConfigurationTest = new SF_CoreConfigurationTest();
    $CoreConfigurationTest->testWriteAndRead();

?>