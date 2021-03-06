<?php

$startTime = \microtime(true);

$errors = array();

$errorCallback = function($severity, $message, $file = null, $line = null, $context = null) use (&$errors) {

    $normalizeSeverity = function() use ($severity) {
        $severityMap = array(
            E_WARNING => 'E_WARNING',
            E_NOTICE => 'E_NOTICE',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED'
        );
        if (\array_key_exists($severity, $severityMap)) {
            return $severityMap[$severity];
        }
        return 'E_UNKOWN_SEVERITY';
    };

    $index = \count($errors);
    $errors[$index]['severity'] = $normalizeSeverity();
    $errors[$index]['message'] = $message;
    $errors[$index]['file'] = $file;
    $errors[$index]['line'] = $line;

    // here to return an error if improper type hints are passed
    $unhandledSeverity = array(E_RECOVERABLE_ERROR);
    if (\in_array($severity, $unhandledSeverity)) {
        return false;
    }

};

\set_error_handler($errorCallback);

include $libsPath . '/SprayFire/Core/ClassLoader.php';

$ClassLoader = new \SprayFire\Core\ClassLoader();
\spl_autoload_register(array($ClassLoader, 'load'));
$ClassLoader->registerNamespaceDirectory('SprayFire', $libsPath);

$paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
$PathGenBootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($paths);
$PathGenBootstrap->runBootstrap();

$Directory = $PathGenBootstrap->getPathGenerator();

// We are setting exception handler here because later in the bootstrap if it is
// found that we cannot continue with processing the request we will simply throw
// an uncaught exception that will wind up sending an internal error response.
$uncaughExceptionContent = $Directory->getWebPath('500.html');
$SystemLogger = new \SprayFire\Logger\SystemLogger();
$ExceptionHandler = new \SprayFire\Core\Handler\ExceptionHandler($SystemLogger, $uncaughExceptionContent);
\set_exception_handler(array($ExceptionHandler, 'trap'));

$primaryConfigPath = $Directory->getConfigPath($primaryConfigFile);
$routesConfigPath = $Directory->getConfigPath($routesConfigFile);
$configObject = '\\SprayFire\\Config\\JsonConfig';

$configs = array();

$configs[0]['config-object'] = $configObject;
$configs[0]['config-data'] = $primaryConfigPath;
$configs[0]['map-key'] = 'PrimaryConfig';

$configs[1]['config-object'] = $configObject;
$configs[1]['config-data'] = $routesConfigPath;
$configs[1]['map-key'] = 'RoutesConfig';

$ConfigErrorLog = new \SprayFire\Logger\DevelopmentLogger();
$ConfigBootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($ConfigErrorLog, $configs);
$ConfigBootstrap->runBootstrap();
$ConfigMap = $ConfigBootstrap->getConfigs();

$configErrors = $ConfigErrorLog->getMessages();

$PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');
$RoutesConfig = $ConfigMap->getObject('RoutesConfig');

$Container = new \SprayFire\Core\Structure\GenericMap();
$Container->setObject('PrimaryConfig', $PrimaryConfig);
$Container->setObject('RoutesConfig', $RoutesConfig);

return $Container;