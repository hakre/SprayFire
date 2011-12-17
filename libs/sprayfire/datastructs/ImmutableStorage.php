<?php

/**
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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\datastructs;

/**
 * A simple key/value storage object that does not allow the data associated with
 * to be changed after the object has been constructed.
 */
class ImmutableStorage extends \libs\sprayfire\datastructs\DataStorage {

    /**
     * Accepts an array of data to store and gives the calling code the option to
     * convert all inner arrays into ImmutableStorage objects.
     *
     * @param array $data
     * @param type $convertDeep
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
     * @param string $key
     * @param mixed $value
     * @throws \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    protected function set($key, $value) {
        throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Attempting to set the value of an immutable object.');
    }

    /**
     * @param type $key
     * @throws \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    protected function removeKey($key) {
        throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Attempting to remove the value of an immutable object.');
    }

    /**
     * Is responsible for returning an array where all internal arrays have been
     * converted to ImmutableStorage objects.
     *
     * @param array $data
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
     * Will loop through each of the values and recursively convert those values
     * that are arrays into ImmutableStorage objects; returning back an array of
     * key/value pairs and ImmutableStorage objects of deeper key/value pairs.
     *
     * @param array $data
     * @return \libs\sprayfire\datastructs\ImmutableStorage
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
