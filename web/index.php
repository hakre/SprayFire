<?php

/**
* SprayFire is a custom built framework intended to ease the development
* of websites with PHP 5.3.
*
* SprayFire is released under the Open-Source Initiative MIT license.
*
* @author Charles Sprayberry <cspray at gmail dot com>
* @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
* @copyright Copyright (c) 2011,2012 Charles Sprayberry
*/

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// The below variables can be changed to adjust the implementation details
// of the framework's initialization process.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// The path where the app, libs, config and web directories are stored
$installPath = \dirname(__DIR__);

// The path where SprayFire and third-party libs are stored
$libsPath = $installPath . '/libs';

// The path where your app files and classes are stored
$appPath = $installPath . '/app';

// The path where the error and stat logs should be stored, should be writable
$logsPath = $installPath . '/logs';

// The path holding the configuration file needed by SprayFire
$configPath = $installPath .'/config';

// The path holding web accessible files
$webPath = $installPath . '/web';

// The sub-directory and file name holding the primary configuration
// This should exists in \a $configPath
$primaryConfigFile = array('json', 'configuration.json');

$routesConfigFile = array('json', 'routes.json');

// PLEASE DO NOT CHANGE CODE BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING!

// SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE

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

$PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');
$RoutesConfig = $ConfigMap->getObject('RoutesConfig');

var_dump($RoutesConfig->defaults);

/**
 * @todo The following markup eventually needs to be moved into the default
 * template for HtmlResponders.
 */

// NOTE: The below code is a temporary measure until the templating system is
// in place

$installDir = \basename($installPath);

if (empty($sanityFailures)) {
    $sanityDisplay = '<div id="sanity-check">';
    $sanityDisplay .= '<div class="sanity-success">';
    $sanityDisplay .= '<img src="/' . $installDir . '/web/images/sanity-success.png" width="75" height="75" style="float:left;margin-right:.5em;" />';
    $sanityDisplay .= '<p>All sanity checks passed!  Please feel free to adjust this template as needed.</p>';
    $sanityDisplay .= '</div>';
    $sanityDisplay .= '</div>';
} else {
    $sanityDisplay = '<div id="sanity-check">';
    foreach ($sanityFailures as $sanityMessage) {
        $sanityDisplay .= '<div class="sanity-failure">';
        $sanityDisplay .= '<img src="/' . $installDir . '/web/images/sanity-warning.png" width="75" height="75" style="float:left;margin-right:.5em;" />';
        $sanityDisplay .= '<p>' . $sanityMessage . '</p>';
        $sanityDisplay .= '</div>';
    }
    $sanityDisplay .= '</div>';
}

echo <<<HTML
<!DOCTYPE html>
    <html>
        <head>
            <title>Welcome to SprayFire!</title>
            <link href="/$installDir/web/css/sprayfire.style.css" rel="stylesheet" type="text/css" />
        </head>
        <body>
            <div id="content">
                <div id="header">
                    <img src="/$installDir/web/images/sprayfire-logo-medium.png" id="sprayfire-logo" alt="SprayFire logo" width="75" height="75" />
                    <h1><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> {$PrimaryConfig->SprayFire->version}</h1>
                </div>

                <div id="body">
                    <div id="main-content">
                        <p>Thanks for checking out <span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span>, a PHP 5.3+ framework written to ease the development of web sites and web applications.  It appears you are setting up a fresh installation, check out the results of the sanity check below.  If you get the green light please feel free to change this template located in <em>put path to template here</em>.  If you find errors please fix them as appropriate and reload this page until you get the green light.</p>
                        $sanityDisplay
                        <p><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> is a fully unit-tested, light-weight PHP framework for developers who want to make simple, secure, dynamic website content.  Some of the features built in includes:</p>
                        <ul>
                            <li>Full support of PHP 5.3+, most importantly including the use of namespaces.</li>
                            <li>Prepared statements for all CRUD functionality.</li>
                            <li>Automatic escaping of output on responses.</li>
                            <li>A simple but flexible templating system using built in PHP functionality.</li>
                            <li>An interface driven, fully extensible API allowing virtually any component of the framework to be implemented at the application's needs.</li>
                            <li>A completely domain driven Model interpretation utilizing a custom-built data persistence solution.</li>
                            <li>Easy support for outside plugins and libraries that support PHP 5.3+.</li>
                            <li>A reimagining of the normal "MVC" design pattern into something we here at <span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> like to call "MRC", or Model-Response-Controller.  Please check out the wiki for more information on exactly how we interpret this design pattern.</p>
                        </ul>
                        <p>For more information please check out:</p>
                        <p style="font-size:1.25em;margin-left:.5em;"><a href="http://www.github.com/cspray/SprayFire/">Source Code</a></p>
                        <p style="font-size:1.25em;margin-left:.5em;"><a href="http://www.github.com/cspray/SprayFire/wiki/">The Wiki</a></p>
                    </div>
                    <div id="sidebar">
                        <div id="the-team">
                            <h2>the team</h2>
                            <div class="team-member">
                                <p class="name">Charles Sprayberry</p>
                                <p class="title">Benevolent Dictator for Life</p>
                                <p class="title">Lead Developer &amp; Creator</p>
                                <p><a href="http://www.github.com/cspray/">github</a></p>
                                <p><a href="http://www.twitter.com/charlesspray/">@charlesspray</a></p>
                            </div>
                            <div class="team-member">
                                <p class="name">Dyana Stewart</p>
                                <p class="title">Graphic Designer</p>
                                <p><a href="http://www.twitter.com/Dy249/">@Dy249</a></p>
                            </div>
                        </div>
                        <div id="credits">
                            <p>A special thanks should go out to the regulars in the <a href="http://chat.stackoverflow.com/rooms/11/php">chat.stackoverflow PHP room</a>.  Of particular note, are <a href="http://www.stackoverflow.com/">Stack Overflow</a> members <a href="http://stackoverflow.com/users/285578/edorian">edorian</a> and <a href="http://stackoverflow.com/users/338665/ircmaxell">ircmaxell</a>.  Without their guidance <span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> would be a much crappier project.</p>
                        </div>
                    </div>
                </div>

                <div id="footer">
                    <p style="text-align:center;"><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> &copy; Charles Sprayberry 2011</p>
                </div>
            </div>
        </body>
    </html>
HTML;

var_dump($errors);
