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

/**
 *
 */
class MutableStorageTest extends SprayFireTestCase {

    public function setUp() {
        parent::setUp();
        if (!class_exists('TestObject')) {
            include 'TestObject.php';
        }
    }

    public function testMutableStorage() {
        $data = array();
        $data['key-one'] = 'value';
        $data['key-two'] = 'another value';
        $data['key-three'] = array('one' => '1');
        $Storage = new \SprayFire\Datastructs\MutableStorage($data, false);

        $this->assertSame(\count($Storage), \count($data));

        $this->assertSame($data['key-one'], $Storage->{'key-one'});

        $newValue = 'a different value';
        $Storage->{'key-one'} = $newValue;
        $this->assertSame($newValue, $Storage['key-one']);

        $Storage->{'key-four'} = 'Roll Tide!';
        $this->assertSame(4, \count($Storage));

        unset($Storage['key-one']);
        $this->assertFalse(isset($Storage->{'key-one'}));

        $this->assertSame(3, \count($Storage));

        $this->assertTrue(is_array($Storage->{'key-three'}));

    }

    public function testMutableStorageDeep() {

        $data = array(
            'key-one' => array(
                'one' => 'value 1'
            ),
            'key-two' => array(
                'one' => 'value 2',
                'two' => array(
                    'key' => 'value 3'
                )
            )
        );

        $Storage = new \SprayFire\Datastructs\MutableStorage($data);
        $this->assertSame('value 1', $Storage->{'key-one'}->one);
    }

    public function testNullArrayKeyStorage() {
        $data = array();
        $Storage = new \SprayFire\Datastructs\MutableStorage($data);

        $First = new TestObject();
        $Second = new TestObject();

        $Storage[] = $First;
        $Storage[] = $Second;

        $this->assertSame(\count($Storage), 2);

        $this->assertSame($First, $Storage[0]);
        $this->assertSame($Second, $Storage[1]);
    }

}