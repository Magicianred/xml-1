<?php

namespace Selective\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Selective\Xml\XmlFormater;

/**
 * Class XmlFormaterTest.
 */
class XmlFormaterTest extends TestCase
{
    /**
     * @var XmlFormater
     */
    protected $xmlFormater;

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    protected function setUp()
    {
        $this->xmlFormater = new XmlFormater();
        $this->root = vfsStream::setup('root');
    }

    protected function tearDown()
    {
        unset($this->xmlFormater);
        $this->xmlFormater = new XmlFormater();
    }

    public function testFormatString()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Do not forget me this weekend!</body></note>';
        $dom = $this->xmlFormater->formatString($content);
        $this->assertNotSame($content, $dom->saveXML());
    }

    public function testFormatFileWithNoDestinationFile()
    {
        $content = file_get_contents(__DIR__ . '/note.xml');
        $dom = $this->xmlFormater->formatFile(__DIR__ . '/note.xml');
        $this->assertNotSame($content, $dom->saveXML());
    }

    public function testFormatFileWithDestinationFile()
    {
        $this->assertSame(
            165,
            $this->xmlFormater->formatFile(__DIR__ . '/note.xml')->save(vfsStream::url('root/note_format.xml'))
        );
    }
}
