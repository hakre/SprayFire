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
namespace SprayFire\Core\Handler;

/**
 * @brief A class that accepts a log and the absolute path to a content replacement
 * file, will log the uncaught exception info and then display whatever content is
 * held in the file passed.
 *
 * @details
 * This class will log the appropriate exception information and then include the
 * file set by the \a $contentReplacementPath injected into the constructor.  In
 * addition, a 500 HTTP status will be returned to the user.  For the interim, it
 * is expected that the content returned is HTML.
 *
 * @todo Eventually we need to refactor this so that instead of a path being injected
 * a Responder object is instantiated instead.  Doing this also has implications
 * that a factory will have to be available before the uncaught exception handler
 * can be properly set.
 *
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Logger.CoreObject
 */
class ExceptionHandler extends \SprayFire\Logger\CoreObject {

    /**
     * @brief A URL path to a page that should handle 500 requests
     *
     * @property $urlPath
     */
    protected $replacePath;

    public function __construct(\SprayFire\Logger\Log $Log, $contentReplacementPath) {
        parent::__construct($Log);
        $this->replacePath = $contentReplacementPath;
    }

    /**
     * @brief It should be known that after the Exception information is logged
     * the request will have a 500 HTTP status error returned and the passed
     * \a $replacePath will be included as the content to display to the user.
     *
     * @param $Exception Exception thrown and not caught
     */
    public function trap($Exception) {
        $file = $Exception->getFile();
        $line = $Exception->getLine();
        $message = $Exception->getMessage();
        $logMessage = 'file:=' . $file . '|line:=' . $line . '|message:=' . $message;
        $this->log($logMessage);
        \header('HTTP/1.1 500 Internal Server Error');
        \header('Content-Type: text/html; charset=UTF-8');
        if (\file_exists($this->replacePath)) {
            include $this->replacePath;
        } else {
            echo $this->getDefaultMarkup();
        }
        exit;
    }

    /**
     * @brief This returns the content to be sent to the user if the content path
     * injected could not properly be included.
     *
     * @return HTML markup
     */
    protected function getDefaultMarkup() {
        return <<<HTML
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8" />
            <title>SprayFire Fatal Error</title>
        </head>
        <body>
            <h1>Oops, we goofed!</h1>
            <p>Sorry, but it appears that we may be experiencing some unforeseen
            issues!  We apologize we couldn't get you to the content that you wanted
            but please try back again next time!  We'll be sure to be right on this
            and have the site back up as soon as possible!</p>
        </body>
    </html>
HTML;

    }

}