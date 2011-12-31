<?php

/**
 * @file
 * @brief A class that extends SprayFire.Core.Structure.DataStorage and allows
 * for any data to be manipulated, removed or added to the structure.
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

namespace SprayFire\Core\Structure;

/**
 * @brief A simple data storage object that holds key/value pairs and allows additional
 * keys to be added and existing keys to be manipulated or removed.
 */
class MutableStorage extends \SprayFire\Core\Structure\DataStorage {

    /**
     * @brief Requires an array of data is passed, optionally allowing the
     * arrays within that data to be converted to MutableStorage objects as
     * well.
     *
     * @param $data An array of data to store
     * @param $convertDeep True if arrays within the array should be converted to
     *                     objects as well.
     * @throws UnexpectedValueException If extending objects do not properly return
     *                                  an array from convertDataDeep
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
     * @return mixed
     */
    protected function set($key, $value) {
        if (\is_null($key)) {
            $key = \count($this->data);
        }
        return $this->data[$key] = $value;
    }

    /**
     * @param $key string
     */
    protected function removeKey($key) {
        if ($this->keyInData($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * @brief Converts all arrays in \a $data \a to MutableStorage objects,
     * allowing for the chaining of properties in the created object.
     *
     * @details
     * Note that if you extend MutableStorage and override this method an array
     * value MUST be returned or an UnexpectedValueException will be thrown by
     * the class constructor.  If self::__construct() is overridden as well
     * and the data from convertDataDeep is not checked you will receive a
     * type hint compile error if a non-array is passed to parent::__construct().
     *
     * @param $data array
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
     * @brief Will convert the passed array, and all arrays within that array,
     * to a SprayFire.Datastructs.MutableStorage object.
     *
     * @param $data array
     * @return SprayFire.Core.Structures.MutableStorage
     */
    private function convertArrayToMutableObject(array $data) {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->convertArrayToMutableObject($value);
            }
        }
        return new MutableStorage($data);
    }

}