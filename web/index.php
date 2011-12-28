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
    // The below variables can be changed and manipulated to adjust the implementation
    // details of the framework's initialization process.
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    /**
     * @var $rootDir The primary installation path for the app
     */
    $rootDir = \dirname(__DIR__);

    // Please do not include any trailing slashes on directories!

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

    // PLEASE DO NOT CHANGE CODE BELOW THIS LINE!

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

    $SanityCheck = new \SprayFire\Core\SanityCheck();
    $sanityFailures = $SanityCheck->verifySanity();

    $errorLogPath = \SprayFire\Core\Directory::getLogsPath('errors.txt');
    $ErrorLogFile = new \SplFileInfo($errorLogPath);
    try {
        $ErrorLog = new \SprayFire\Logger\FileLogger($ErrorLogFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        // This is a fail-safe to ensure that there is an ErrorLog for various
        // objects needing to log error messages
        $ErrorLog = new \SprayFire\Logger\FailSafeLogger();
    }

    $primaryConfigPath = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json');
    $PrimaryConfigFile = new \SplFileInfo($primaryConfigPath);
    try {
        $PrimaryConfig = new \SprayFire\Config\JsonConfig($PrimaryConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        /**
         * @todo This needs to be changed so that the basic values we are looking
         * for are created in an ArrayConfig object
         */
        $configData = array();
        $configData['framework'] = array();
        $configData['framework']['version'] = '0.1.0-alpha';

        $configData['app'] = array();
        $configData['app']['version'] = '0.0.0-e';
        $configData['app']['development-mode'] = 'off';

        $PrimaryConfig = new \SprayFire\Config\ArrayConfig($configData);
    }

    $routesConfigPath = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'routes.json');
    $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
    try {
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        $data = array();
        $data['defaults'] = array();
        $data['defaults']['controller'] = 'pages';
        $data['defaults']['action'] = 'index';
    }

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
                    <h1><span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> {$PrimaryConfig->framework->version}</h1>
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
                                <p><a href="http://www.github.com/cspray/">http://www.github.com/cspray/</a></p>
                                <p><a href="http://www.twitter.com/charlesspray/">http://www.twitter.com/charlesspray/</a></p>
                            </div>
                            <div class="team-member">
                                <p class="name">Dyana Stewart</p>
                                <p class="title">Graphic Designer</p>
                                <p><a href="http://www.twitter.com/Dy249/">http://www.twitter.com/Dy249/</a></p>
                            </div>
                        </div>
                        <div id="credits">
                            <p>While <span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> was written from the ground up with new code and implementations designed by the author, inspiration and ideas were "taken" from other PHP frameworks including:</p>
                            <ul>
                                <li><a href="http://www.cakephp.org/">CakePHP</a></li>
                                <li><a href="https://github.com/zendframework/zf2">Zend Framework 2</a></li>
                                <li><a href="http://symfony.com/">Symfony</a></li>
                            </ul>
                            <p>A special thanks should go out to the regulars in the <a href="http://chat.stackoverflow.com/rooms/11/php">chat.stackoverflow PHP room</a>.  Of particular note, are <a href="http://www.stackoverflow.com/">StackOverFlow</a> members <a href="http://stackoverflow.com/users/285578/edorian">edorian</a> and <a href="http://stackoverflow.com/users/338665/ircmaxell">ircmaxell</a>.  Without their guidance <span class="sprayfire-orange">Spray</span><span class="sprayfire-red">Fire</span> would be a much crappier project.</p>
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
