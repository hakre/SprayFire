<?php

/**
 * @file
 * @brief A file storing the ExceptionHandler used as a callback for set_exception_handler()
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */
namespace SprayFire\Core;

/**
 * @brief A class that will log uncaught exception messages and properly redirect
 * the request to the `500.html` page in the web root.
 */
class ExceptionHandler extends \SprayFire\Logger\CoreObject {

    /**
     * @brief It should be known that after the Exception information is logged
     * the request will be redirected to /web/500.html
     *
     * @param $Exception Exception thrown and not caught
     */
    public function trap($Exception) {
        $file = $Exception->getFile();
        $line = $Exception->getLine();
        $message = $Exception->getMessage();
        $logMessage = 'file:=' . $file . '|line:=' . $line . '|message:=' . $message;
        $this->log($logMessage);

        $location = \SprayFire\Core\Directory::getUrlPath('500.html');
        \header('Location: ' . $location);
        exit;
    }

}