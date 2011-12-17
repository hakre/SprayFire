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
 * A simple data storage object that holds key/value pairs and allows additional
 * keys to be added and existing keys to be manipulated or removed.
 */
class MutableStorage extends \libs\sprayfire\datastructs\DataStorage {

    /**
     * Will accept an array of data to store and gives the calling code an option
     * to recursively convert all inner arrays into MutableStorage objects; ultimately
     * this will allow for chaining within this object.
     *
     * @param array $data
     * @param boolean $convertDeep
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
     * @return mixed
     */
    protected function set($key, $value) {
        if (\is_null($key)) {
            $key = \count($this->data);
        }
        return $this->data[$key] = $value;
    }

    /**
     * @param string $key
     */
    protected function removeKey($key) {
        if ($this->keyInData($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * Will take an array and conver all inner arrays into MutableStorage objects
     * returning the outer array.
     *
     * @param array $data
     * @return array
     */
    protected function convertDataDeep(array $data) {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->convertArrayToMutableObject($value);
            }
        }
        return $data;
    }

    /**
     * Will loop through each of the values and recursively convert those values
     * that are arrays into MutableStorage objects.
     *
     * @param array $data
     * @return \libs\sprayfire\datastructs\MutableStorage
     */
    private function convertArrayToMutableObject(array $data) {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->convertArrayToMutableObject($value);
            }
        }
        return new \libs\sprayfire\datastructs\MutableStorage($data);
    }

}

// End MutableStorage
