<?php

namespace Selective\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Selective\Xml\XmlBeautifier;

/**
 * Test.
 */
class XmlBeautifierTest extends TestCase
{
    /**
     * @var XmlBeautifier
     */
    protected $xmlBeautifier;

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    protected function setUp()
    {
        $this->xmlBeautifier = new XmlBeautifier();
        $this->root = vfsStream::setup('root');
    }

    protected function tearDown()
    {
        unset($this->xmlBeautifier);
        $this->xmlBeautifier = new XmlBeautifier();
    }

    public function testBeautifyString()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Do not forget me this weekend!</body></note>';
        $dom = $this->xmlBeautifier->beautifyString($content);
        $this->assertNotSame($content, $dom->saveXML());
    }

    public function testBeautifyFileWithNoDestinationFile()
    {
        $content = file_get_contents(__DIR__ . '/note.xml');
        $dom = $this->xmlBeautifier->beautifyFile(__DIR__ . '/note.xml');
        $this->assertNotSame($content, $dom->saveXML());
    }

    public function testBeautifyFileWithDestinationFile()
    {
        $this->assertSame(
            165,
            $this->xmlBeautifier->beautifyFile(__DIR__ . '/note.xml')->save(vfsStream::url('root/note_format.xml'))
        );
    }
}
