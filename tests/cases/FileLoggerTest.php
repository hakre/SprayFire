<?php

/**
 * @file
 * @brief
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

if (!interface_exists('\\SprayFire\\Core\\Object')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Object.php';
}
if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
}
if (!interface_exists('\\SprayFire\\Logger\\Log')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Logger/Log.php';
}
if (!class_exists('\\SprayFire\\Logger\\FileLogger')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Logger/FileLogger.php';
}

/**
 * @brief
 */
class FileLoggerTest extends PHPUnit_Framework_TestCase {

    private $readOnlyLog;

    private $writableLog;

    private $noTimestampLog;

    public function setUp() {


        $logPath = \SPRAYFIRE_ROOT . '/tests/mockframework/logs';

        $this->readOnlyLog = $logPath . '/readonly-log.txt';
        $this->writableLog = $logPath . 'writable-log.txt';
        $this->noTimestampLog = $logPath . 'no-timestamp-log.txt';
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadOnlyFileObjectFailure() {
        $file = $this->readOnlyLog;
        \touch($file);
        \chmod($file, 0444);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);
    }

    public function testBasicFileLogging() {
        $file = $this->writableLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Logger->log('test','something');
        $Logger->log('something', 'else');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array('test := something', 'something := else', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame(\trim($line), $expected[$i]);
            $i++;
        }
    }

    public function testEmptyLogTimestamp() {
        $file = $this->noTimestampLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);
        $blankTimestamp = '';
        $Logger->log($blankTimestamp,'something');
        $Logger->log($blankTimestamp, 'else');

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array('00-00-0000 00:00:00 := something', '00-00-0000 00:00:00 := else', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame(\trim($line), $expected[$i]);
            $i++;
        }
    }

    public function testEmptyLogMessage() {
        $file = $this->noTimestampLog;
        \touch($file);
        \chmod($file, 0755);
        $LogFile = new \SplFileInfo($file);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);
        $blankMessage = '';
        $Logger->log('12-24-2011 12:45:12', $blankMessage);
        $Logger->log('12-25-2011 13:56:10', $blankMessage);

        $Log = $LogFile->openFile('r');
        $i = 0;
        $expected = array('12-24-2011 12:45:12 := Blank message.', '12-25-2011 13:56:10 := Blank message.', '');
        while (!$Log->eof()) {
            $line = $Log->fgets();
            $this->assertSame(\trim($line), $expected[$i]);
            $i++;
        }
    }

    public function tearDown() {
        if (\file_exists($this->readOnlyLog)) {
            \unlink($this->readOnlyLog);
        }
        $this->assertFalse(\file_exists($this->readOnlyLog));

        if (\file_exists($this->writableLog)) {
            \unlink($this->writableLog);
        }
        $this->assertFalse(\file_exists($this->writableLog));

        if (\file_exists($this->noTimestampLog)) {
            \unlink($this->noTimestampLog);
        }
        $this->assertFalse(\file_exists($this->noTimestampLog));
    }

}

// End FileLoggerTest

// End libs.sprayfire