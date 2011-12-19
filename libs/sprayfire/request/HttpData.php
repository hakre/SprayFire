<?php

/**
 * @file
 * @brief Holds an interface to allow PHP superglobal objects to be manipulated
 * in an object oriented fashion.
 *
 * @details
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */
namespace libs\sprayfire\request {

    /**
     * @brief An interface for objects that are meant to work with and manipulate
     * the HTTP superglobals available to PHP.
     *
     * @details
     * !! WARNING !! If extending the base framework data storage classes you will
     * also need to provide a property in that class and assign the passed array
     * by reference to that property.
     *
     * @example
     * <pre>
     *      // this is a simple implementation of the HttpData interface
     *      // using one of the already unit tested framework data structures
     *
     *      class BeautifulClassName extends \libs\sprayfire\datastructs\MutableIterator implements \libs\sprayfire\request\HttpData {
     *
     *          // overriding the property used by the data storage objects
     *          protected $data = array();
     *
     *          // NOTE we are not calling parent::__construct() Calling this would overwrite our data!
     *          // The only thing you're losing is converting arrays within the
     *          // superglobals to objects...which is a good thing.  We do not
     *          // want to manipulate the superglobals like that.
     *          public function __construct(array &$data) {
     *              $this->$data =& $data;      // note the `=&`
     *          }
     *
     *      }
     *
     *      // Congratulations!  Your HttpData object is now set up so that changes
     *      // to the object make changes to the array and changes to the array
     *      // make changes to the object
     * </pre>
     */
    interface HttpData extends \ArrayAccess, \Countable, \Iterator, \libs\sprayfire\datastructs\Overloadable {

        /**
         * Assures that the array being passed to the HttpData object are closely linked
         * together; changes to the object change the array injected and changes to
         * the array change the object.
         *
         * @param &$data An array passed by reference
         */
        public function __construct(array &$data);

    }

    // End HttpData
}

// End libs.sprayfire.request