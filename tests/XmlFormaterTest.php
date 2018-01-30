<?php

namespace Odan\Test;

use Odan\Xml\XmlFormater;
use PHPUnit\Framework\TestCase;

class XmlFormaterTest extends TestCase
{
    protected $xmlFormater;

    protected function setUp()
    {
        $this->xmlFormater = new XmlFormater();
    }

    protected function tearDown()
    {
        $this->xmlFormater = null;
        @unlink(__DIR__.'/note_format.xml');
    }

    public function testFormatString()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Do not forget me this weekend!</body></note>';
        $this->assertInternalType('string', $this->xmlFormater->formatString($content));
    }

    public function testFormatFileWithNoDestinationFile()
    {
        $this->assertTrue($this->xmlFormater->formatFile(__DIR__.'/note.xml'));
    }

    public function testFormatFileWithDestinationFile()
    {
        $this->assertTrue($this->xmlFormater->formatFile(__DIR__.'/note.xml', __DIR__.'/note_format.xml'));
    }
}
