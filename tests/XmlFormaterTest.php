<?php

namespace Selective\Test;

use Selective\Xml\XmlFormater;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class XmlFormaterTest extends TestCase
{
    /**
     * @var XmlFormater|null
     */
    protected $xmlFormater;

    /**
     * @var vfsStreamDirectory|null
     */
    protected $root;

    protected function setUp()
    {
        $this->xmlFormater = new XmlFormater();
        $this->root = vfsStream::setup("root");
    }

    protected function tearDown()
    {
        $this->xmlFormater = null;
    }

    public function testFormatString()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Do not forget me this weekend!</body></note>';
        $this->assertInternalType('string', $this->xmlFormater->formatString($content));
    }

    public function testFormatFileWithNoDestinationFile()
    {
        $this->assertTrue($this->xmlFormater->formatFile(__DIR__ . '/note.xml'));
    }

    public function testFormatFileWithDestinationFile()
    {
        $this->assertTrue($this->xmlFormater->formatFile(__DIR__ . '/note.xml', vfsStream::url('root/note_format.xml')));
    }
}
