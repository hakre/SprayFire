<?php

/**
 * @file
 * @brief A simple key/value storage object that extends libs.sprayfire.datastructs.DataStorage
 * and does not allow the data associated to be changed after the object has been
 * constructed.
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

namespace SprayFire\Datastructs;

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
class ImmutableStorage extends \SprayFire\Datastructs\DataStorage {

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
        throw new \SprayFire\Exceptions\UnsupportedOperationException('Attempting to set the value of an immutable object.');
    }

    /**
     * @param $key string
     * @throws libs.sprayfire.exceptions.UnsupportedOperationException
     */
    protected function removeKey($key) {
        throw new \SprayFire\Exceptions\UnsupportedOperationException('Attempting to remove the value of an immutable object.');
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
        return new \SprayFire\Datastructs\ImmutableStorage($data);
    }

}