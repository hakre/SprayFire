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
    class SF_ClassLoader {

        public function __construct() {

        }

        /**
         * Sets the appropriate framework and application autoload functions to
         * the autoload register.
         */
        public function setAutoloader() {
            $frameworkCallback = array($this, 'frameworkAutoload');

            spl_autoload_register($frameworkCallback);
        }

        /**
         * If the class is a framework class will determine which sub-directory
         * the class is inside the framework folder and will attempt to include
         * that file using an absolute path.
         *
         * @param string $className
         */
        private function frameworkAutoload($className) {
            $isFrameworkClass = $this->isFrameworkClass($className);
            if (!$isFrameworkClass) {
                return;
            }

            $isFrameworkException = $this->isFrameworkException($className);
            if ($isFrameworkException) {
                $this->doFrameworkExceptionInclude($className);
                return;
            }

            $isFrameworkInterface = $this->isFrameworkInterface($className);
            if ($isFrameworkInterface) {
                $this->doFrameworkInterfaceInclude($className);
                return;
            }

            $this->doFrameworkCoreInclude($className);
        }

        /**
         * Determines if the $className belongs to a class considered part of the
         * framework.
         *
         * @param string $className
         * @return boolean
         */
        private function isFrameworkClass($className) {
            $regexPattern = '/^(SF_).+$/';
            $numMatches = preg_match($regexPattern, $className);
            return $this->didRegexMatch($numMatches);
        }

        /**
         * @param string $className
         * @return boolean
         */
        private function isFrameworkException($className) {
            $regexPattern = '/^(SF_).+(Exception)$/';
            $numMatches = preg_match($regexPattern, $className);
            return $this->didRegexMatch($numMatches);
        }

        /**
         * @param string $className
         * @return boolean
         */
        private function isFrameworkInterface($className) {
            $regexPattern = '/^(SF_Is).+$/';
            $numMatches = preg_match($regexPattern, $className);
            return $this->didRegexMatch($numMatches);
        }

        /**
         * @param string $className
         */
        private function doFrameworkExceptionInclude($className) {
            $directory = FRAMEWORK_PATH . DS . 'exceptions';
            $this->includeClassFromDirectory($className, $directory);
        }

        /**
         * @param string $className
         */
        private function doFrameworkInterfaceInclude($className) {
            $directory = FRAMEWORK_PATH . DS . 'interfaces';
            $this->includeClassFromDirectory($className, $directory);
        }

        /**
         * @param string $className
         */
        private function doFrameworkCoreInclude($className) {
            $directory = FRAMEWORK_PATH . DS . 'core';
            $this->includeClassFromDirectory($className, $directory);
        }


        /**
         * A generic include function that will take a className, convert it to
         * the appropriate fileName, and a directory and attempt to include that
         * file.
         *
         * Please note, the directory passed should not have the directory seperator
         * appended to the end.  Only pass the complete path to the directory,
         * with no trailing directory seperators.
         *
         * @param string $className
         * @param string $directory
         */
        private function includeClassFromDirectory($className, $directory) {
            $fileName = $className . '.php';
            $path = $directory . DS . $fileName;
            include $path;
        }

        private function appAutoload($className) {

        }

        /**
         * Converts the preg_match return value to an appropriate true/false value
         * based on the rules stated in the PHP manual for preg_match.
         *
         * @param integer $numMatches
         * @return boolean
         */
        private function didRegexMatch($numMatches) {
            if (0 >= $numMatches) {
                return false;
            } else {
                return true;
            }
        }

    }

// End SF_ClassLoader
