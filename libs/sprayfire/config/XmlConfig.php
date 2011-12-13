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

namespace libs\sprayfire\config;

/**
 * Allows for the importing of XML configuration documents, through the implementation
 * of `importConfig()` and provides extending classes the ability to traverse the
 * document passed utilizing a SimpleXMLElement of the document.
 *
 * @uses \SimpleXMLElement
 * @uses \libxml
 * @uses \ReflectionClass
 * @uses \InvalidArgumentException
 * @uses \libs\sprayfire\exceptions\InvalidConfigurationException
 * @uses \libs\sprayfire\exceptions\UnsupportedOperationException
 * @uses \libs\sprayfire\config\BaseConfig
 * @uses \libs\sprayfire\interfaces\Configuration
 * @uses \libs\sprayfire\core\CoreObject
 * @uses \libs\sprayfire\interfaces\Object
 */
abstract class XmlConfig extends BaseConfig {

    /**
     * The value for true for ini settings that expect a boolean value
     *
     * @var int
     */
    const INI_YES = 1;

    /**
     * The value for false for ini settings that expect a boolean value
     *
     * @var int
     */
    const INI_NO = 0;

    /**
     * The value for true in the configuration files.
     *
     * @var string
     */
    const CONFIG_YES = 'yes';

    /**
     * The value for false in the configuration files.
     *
     * @var string
     */
    const CONFIG_NO = 'no';

    /**
     * Holds a reflection of the SimpleXMLElement class, allowing new SimpleXMLElement
     * objects to be created
     *
     * @var ReflectionClass
     */
    private $ReflectedSimpleXml;

    /**
     * This is the object representing our XML document.
     *
     * @var SimpleXMLElement
     */
    protected $XmlConfiguration;

    /**
     * Will create a reflection of a SimpleXMLElement to serve as a "factory" of
     * sorts for SimpleXMLElements.
     *
     * @throws libs\sprayfire\exception\InvalidConfigurationException
     */
    public function __construct() {
        try {
            $this->ReflectedSimpleXml = new \ReflectionClass('\\SimpleXMLElement');
        } catch (\ReflectionException $ReflectExc) {
            throw new \libs\sprayfire\exceptions\InvalidConfigurationException('There was an error instantiating the parser for this configuration object.', null, $ReflectExc);
        }
    }

    /**
     * Will create a new SimpleXMLElement object, passing the $filePath as the name
     * of a file on the system, ensure that there were no XML errors and assign
     * the object to the $XmlConfiguration property.
     *
     * Please note that the XML document must validate against the Document Type
     * Definition associated with it or an exception will be thrown.  Any XML error
     * will wind up having an exception thrown, if more than one error occurred the
     * last error will be thrown with previous errors stored in the exceptions
     * $previous parameter.
     *
     * It is important that any class overriding this method ensures to call
     * parent::importConfig($filePath) in the constructor.  This should likely be
     * the first thing your constructor does, to ensure that the XmlConfiguration
     * property has a value set in it.
     *
     * @param string $filePath
     * @throws \InvalidArgumentException
     */
    public function importConfig($filePath) {
        try {
            \libxml_use_internal_errors(true);
            $NewSimpleXml = $this->ReflectedSimpleXml->newInstance($filePath, \LIBXML_DTDVALID, true);
            $xmlErrors = \libxml_get_errors();
            $InvalidArgExc = null;
            foreach ($xmlErrors as $xmlErr) {
                $InvalidArgExc = new \InvalidArgumentException($xmlErr->message, null, $InvalidArgExc);
            }
            \libxml_clear_errors();
            \libxml_use_internal_errors(false);
            if (isset($InvalidArgExc)) {
                throw $InvalidArgExc;
            }
            $this->XmlConfiguration = $NewSimpleXml;

        // Is thrown if there is an error creating the new class from the reflected SimpleXMLElement
        } catch (\ReflectionException $ReflectExc) {
            throw new \InvalidArgumentException('The file path was invalid or otherwise caused the parser object to not be created. ' . $ReflectExc->getMessage(), null, $ReflectExc);

        // Is thrown if there is an error parsing the $filePath, to include the file not existing.
        // This exception is thrown by SimpleXMLElement if the first parameter is not a valid XML document
        // or there is a problem opening the file.
        } catch (\Exception $Exc) {
            throw new \InvalidArgumentException('The file path was invalid or otherwise caused the parser object to not be created. ' . $Exc->getMessage(), null, $Exc);
        }
    }

}

// End XmlConfig
