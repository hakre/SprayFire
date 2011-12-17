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
class SprayFireObjectStoreTest extends PHPUnit_Framework_TestCase {

    public function testBasicObjectStorage() {
        $ParentType = new ReflectionClass('\\tests\\helpers\\TestObject');
        $Storage = new libs\sprayfire\datastructs\SprayFireObjectStore($ParentType);

        $expectedInitiationSize = 0;
        $initiationSize = \count($Storage);
        $this->assertSame($expectedInitiationSize, $initiationSize);
        $this->assertTrue($Storage->isEmpty());

        $FirstAdd = new \tests\helpers\TestObject();
        $Storage->{'object-one'} = $FirstAdd;

        $expectedSizeAfterFirstAdd = 1;
        $sizeAfterFirstAdd = \count($Storage);
        $this->assertSame($expectedSizeAfterFirstAdd, $sizeAfterFirstAdd);
        $this->assertFalse($Storage->isEmpty());

        $expectedFirstAddIndex = 'object-one';
        $firstAddIndex = $Storage->indexOf($FirstAdd);
        $this->assertSame($expectedFirstAddIndex, $firstAddIndex);

        $SecondAdd = new \tests\helpers\TestObject();

        $this->assertFalse($Storage->contains($SecondAdd));

        $Storage['object-two'] = $SecondAdd;

        $expectedSizeAfterSecondAdd = 2;
        $sizeAfterSecondAdd = $Storage->count();
        $this->assertSame($expectedSizeAfterSecondAdd, $sizeAfterSecondAdd);
        $this->assertTrue($Storage->contains($SecondAdd));

        $SecondFromGetObject = $Storage->getObject('object-two');
        $this->assertSame($SecondAdd, $SecondFromGetObject);

        $InvalidObject = new \libs\sprayfire\datastructs\ImmutableStorage(array());
        $exceptionThrown = false;
        try {
            $Storage->setObject('invalid-object', $InvalidObject);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
        $this->assertFalse($Storage->contains($InvalidObject));
        $this->assertTrue((\count($Storage)) === 2);
    }

    public function testInterfaceObjectStorage() {
        $Type = new \ReflectionClass('\\libs\\sprayfire\\datastructs\\Overloadable');
        $Storage = new libs\sprayfire\datastructs\SprayFireObjectStore($Type);

        $Storage->{'first-object'} = new \libs\sprayfire\datastructs\ImmutableStorage(array());
        $this->assertTrue($Storage->count() === 1);
    }

    public function testLoopingThroughObjectStore() {
        $Type = new \ReflectionClass('\\libs\\sprayfire\\core\\Object');
        $Storage = new libs\sprayfire\datastructs\SprayFireObjectStore($Type);

        $One = new \tests\helpers\TestObject();
        $Two = new \tests\helpers\TestObject();
        $Three = new \tests\helpers\TestObject();
        $Four = new \tests\helpers\TestObject();
        $Five = new \tests\helpers\TestObject();

        $Storage->one = $One;
        $Storage['two'] = $Two;
        $Storage->setObject('three', $Three);
        $Storage->four = $Four;

        $Storage->two = $Five;

        $this->assertTrue($Storage->contains($Five));

        $loopRan = false;
        $expectedKeys = array('one', 'three', 'four');
        $expectedObjects = array($One, $Three, $Four);
        $i = 0;
        foreach ($Storage as $key => $value) {
            if (!$loopRan) {
                $loopRan = true;
            }
            if ($key === 'two') {
                unset($Storage->two);
                continue;
            }
            $this->assertSame($expectedKeys[$i], $key, 'The value of key is ' . $key . ' and the expected key is ' . $expectedKeys[$i]);
            $this->assertSame($expectedObjects[$i], $value);
            $i++;
        }

        $this->assertTrue($loopRan);
        $this->assertTrue($Storage->count() === 3);
    }

    public function testNumericIndexObjectStore() {
        $Type = new ReflectionClass('\\tests\\helpers\\TestObject');
        $Storage = new libs\sprayfire\datastructs\SprayFireObjectStore($Type);

        $First = new \tests\helpers\TestObject();
        $Second = new \tests\helpers\TestObject();
        $Three = new \tests\helpers\TestObject();

        $Storage[] = $First;

        $this->assertTrue($Storage->count() === 1);
        $this->assertSame($First, $Storage[0]);

        $Storage[] = $Second;

        $this->assertTrue(\count($Storage) === 2);
        $this->assertSame($Second, $Storage[1]);

        $Storage[] = $Three;

        $expectedIndexes = array(0, 1, 2);
        $expectedObjects = array($First, $Second, $Three);
        $i = 0;
        foreach ($Storage as $key => $value) {
            $this->assertSame($key, $expectedIndexes[$i]);
            $this->assertSame($value, $expectedObjects[$i]);
            $i++;
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSneakyDevAddingNonFrameworkObjects() {

        // note that the object type is not a framework object!
        $Type = new \ReflectionClass('\stdClass');
        $Storage = new libs\sprayfire\datastructs\SprayFireObjectStore($Type);
        $Storage->mykey = new \stdClass();
    }

}

// End SprayFireObjectStoreTest
