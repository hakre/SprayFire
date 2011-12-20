<?php

/**
 * @file
 * @brief A simple key/value storage object that extends libs.sprayfire.datastructs.DataStorage
 * and does not allow the data associated to be changed after the object has been
 * constructed.
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
 * @namespace libs.sprayfire.datastructs
 * @brief Holds the API used by the framework to store and transfer sets of data.
 */
namespace libs\sprayfire\datastructs {

    /**
     * @brief An object that allows for data to be stored and to be assured that
     * the data is not mutable.
     *
     * @details
     * This object is immutable by the fact that after the object is constructed
     * attempting to __set the object or offsetSet the object's properties will
     * results in a libs.sprayfire.exceptions.UnsupportedOperationException will
     * be thrown.  If a class extends this please ensure that it is a truly immutable
     * object and does not have any "backdoors".
     */
    class ImmutableStorage extends \libs\sprayfire\datastructs\DataStorage {

        /**
         * @brief Accepts an array of data to store and gives the calling code the option to
         * convert all inner arrays into ImmutableStorage objects.
         *
         * @param $data array
         * @param $convertDeep boolean
         */
        public function __construct(array $data, $convertDeep = true) {
            if ((boolean) $convertDeep) {
                $data = $this->convertDataDeep($data);
            }
            if (!\is_array($data)) {
                throw new \UnexpectedValueException('The data returned from convertDataDeep must be an array.');
            }
            parent::__construct($data);
        }

        /**
         * @param $key string
         * @param $value mixed
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        protected function set($key, $value) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Attempting to set the value of an immutable object.');
        }

        /**
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        protected function removeKey($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Attempting to remove the value of an immutable object.');
        }

        /**
         * @brief Converts all arrays in \a $data \a to ImmutableStorage objects,
         * allowing for the chaining of properties in the created object.
         *
         * @details
         * Note that if you extend ImmutableStorage and override this method an array
         * value MUST be returned or a libs.sprayfire.exceptions.UnexpectedValueException
         * will be thrown by the class constructor.  If self::__construct() is overridden
         * as well and the data from convertDataDeep is not an array you will receive a
         * type hint compile error when parent::__construct() is called.
         *
         * @param $data array
         * @return array
         */
        protected function convertDataDeep(array $data) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    $data[$key] = $this->convertArrayToImmutableObject($value);
                }
            }
            return $data;
        }

        /**
         * @brief Will convert the passed array, and all arrays within that array,
         * to a libs.sprayfire.datastructs.ImmutableStorage object.
         *
         * @param $data array
         * @return libs.sprayfire.datastructs.ImmutableStorage
         */
        private function convertArrayToImmutableObject(array $data) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    $data[$key] = $this->convertArrayToImmutableObject($value);
                }
            }
            return new \libs\sprayfire\datastructs\ImmutableStorage($data);
        }

    }

    // End ImmutableStorage

}

// End libs.sprayfire.datastructs