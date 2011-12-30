<?php

   /**
    * SprayFire is a custom built framework intended to ease the development
    * of websites with PHP 5.3.
    *
    * SprayFire is released under the Open-Source Initiative MIT license.
    *
    * @author Charles Sprayberry <cspray at gmail dot com>
    * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
    * @copyright Copyright (c) 2011, Charles Sprayberry
    */

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // The below variables can be changed to adjust the implementation details
    // of the framework's initialization process.
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // Please do not include any trailing slashes on directories!

    /**
     * @var $rootDir The primary installation path for the app
     */
    $rootDir = \dirname(__DIR__);

    /**
     * @brief WARNING!  Only change this directory if you are sure to move the
     * <code>SprayFire</code> library to that directory.
     *
     * @var $libsPath The path holding SprayFire and third-party libraries
     */
    $libsPath = $rootDir . '/libs';

    /**
     * @var $appPath The path holding app libraries and classes
     */
    $appPath = $rootDir . '/app';

    /**
     * @var $logsPath The path holding log files used by the app or framework
     */
    $logsPath = $rootDir . '/logs';

    /**
     * @var $webPath The path holding web accessible files
     */
    $webPath = $rootDir . '/web';

    /**
     * @var $primaryErrorLogFile The sub-dir and file name that error messages should
     *      be logged in.
     *
     * @see SprayFire.Core.Directory::getLogsPath() for details on how to set the path
     */
    $primaryErrorLogPath = array('errors.txt');

    /**
     * @var $primaryConfigPath The sub-dir and file name that is holding the primary
     *      configuration used by SprayFire
     *
     * @see SprayFire.Core.Directory for details on how to set the path
     */
    $primaryConfigPath = array('SprayFire', 'Config', 'json', 'configuration.json');

    /**
     * @var $routesConfigPath The sub-dir and file name that is holding the routes
     *      configuration used by SprayFire.Request.Router.
     */
    $routesConfigPath = array('SprayFire', 'Config', 'json', 'routes.json');

    // PLEASE DO NOT CHANGE CODE BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING!

    // SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE

    include $libsPath . '/SprayFire/Core/Directory.php';

    // Be sure to set the following paths in \SprayFire\Core\Directory
    // - installPath
    // - libsPath
    // - appPath
    // - logsPath
    // - webPath
    \SprayFire\Core\Directory::setInstallPath($rootDir);
    \SprayFire\Core\Directory::setLibsPath($libsPath);
    \SprayFire\Core\Directory::setAppPath($appPath);
    \SprayFire\Core\Directory::setLogsPath($logsPath);
    \SprayFire\Core\Directory::setWebPath($webPath);

    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'ClassLoader.php');
    $ClassLoader = new \SprayFire\Core\ClassLoader();
    $ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
    \spl_autoload_register(array($ClassLoader, 'loadClass'));

    $ErrorLogFile = new \SplFileInfo(\SprayFire\Core\Directory::getLogsPath($primaryErrorLogPath));
    try {
        $ErrorLog = new \SprayFire\Logger\FileLogger($ErrorLogFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        // This is a fail-safe to ensure that there is an ErrorLog for various objects needing to log error messages
        $ErrorLog = new \SprayFire\Logger\FailSafeLogger();
        $ErrorLog->log(\date('M-d-Y H:i:s'), $InvalArgExc->getMessage());
    }

    $ErrorHandler = new \SprayFire\Core\ErrorHandler($ErrorLog);
    \set_error_handler(array($ErrorHandler, 'trap'));

    $ExceptionHandler = new \SprayFire\Core\ExceptionHandler($ErrorLog);
    \set_exception_handler(array($ExceptionHandler, 'trap'));

    $SanityCheck = new \SprayFire\Core\SanityCheck();
    $SanityCheck->verifySanity();

    $SprayFireDataContainer = new \SprayFire\Datastructs\GenericMap();
    $SprayFireDataContainer->setObject('ErrorHandler', $ErrorHandler);
    $SprayFireDataContainer->setObject('SanityCheck', $SanityCheck);

    $ConfigGatherer = new \SprayFire\Core\ConfigGatherer($primaryConfigPath, $routesConfigPath);
    $PrimaryConfig = $ConfigGatherer->fetchPrimaryConfiguration();
    $RoutesConfig = $ConfigGatherer->fetchRoutesConfiguration();

    $ClassLoader->registerNamespaceDirectory($PrimaryConfig->app->name, \SprayFire\Core\Directory::getAppPath());

    $Router = new \SprayFire\Request\SprayFireRouter($RoutesConfig, $ErrorLog);
    $Uri = new \SprayFire\Request\BaseUri($_SERVER['REQUEST_URI']);
    $RoutedUri = $Router->getRoutedUri($Uri);

    /**
     * @todo The following markup eventually needs to be moved into the default
     * template for HtmlResponders.
     */

    // NOTE: The below code is a temporary measure until the templating system is
    // in place

    $installDir = \basename(\SprayFire\Core\Directory::getInstallPath());

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

var_dump($ErrorHandler->getTrappedErrors());
