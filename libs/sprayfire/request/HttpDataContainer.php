<?php

/**
 * @file
 * @brief Holds an interface used to store the various HTTP related superglobals
 * as objects to later be used by application components.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */
namespace libs\sprayfire\request;
use libs\sprayfire\request\HttpData as HttpData;

    /**
     * @brief An interface used to store various HTTP data sent in the current
     * request.
     */
    interface HttpDataContainer {

        /**
         * @param $Get libs.sprayfire.request.HttpData should be associated with \a $_GET
         */
        public function setGetData(HttpData $Get);

        /**
         * @return libs.sprayfire.request.HttpData should return the object associated with \a $_GET
         */
        public function getGetData();

        /**
         * @param $Post libs.sprayfire.request.HttpData should be associated with \a $_POST
         */
        public function setPostData(HttpData $Post);

        /**
         * @return libs.sprayfire.request.HttpData should return the object associated with \a $_POST
         */
        public function getPostData();

        /**
         * @param $Files libs.sprayfire.request.HttpData should be associated with \a $_FILES
         */
        public function setFilesData(HttpData $Files);

        /**
         * @return libs.sprayfire.request.HttpData should return the object associated with \a $_FILES
         */
        public function getFilesData();

    }

    // End HttpDataContainer

// End libs.sprayfire