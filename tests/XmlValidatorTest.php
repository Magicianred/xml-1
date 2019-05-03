<?php

namespace Selective\Test;

use DOMDocument;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Selective\Xml\XmlValidator;

/**
 * Class XmlValidatorTest.
 */
class XmlValidatorTest extends TestCase
{
    /**
     * @var XmlValidator
     */
    protected $xmlValidator;

    protected function setUp()
    {
        $this->xmlValidator = new XmlValidator();
    }

    protected function tearDown()
    {
        unset($this->xmlValidator);
        $this->xmlValidator = new XmlValidator();
    }

    public function testValidateFile()
    {
        $this->assertInternalType('array', $this->xmlValidator->validateFile(__DIR__ . '/note.xml', __DIR__ . '/schema.xsd'));
    }

    public function testValidateFileWithInvalidXsd()
    {
        $result = $this->xmlValidator->validateFile(__DIR__ . '/note.xml', __DIR__ . '/invalid_schema.xsd');
        $this->assertInternalType('array', $result);
        $this->assertSame(2, $result[0]->level);
    }

    public function testValidateDom()
    {
        $xml = new DOMDocument();
        $xml->load(__DIR__ . '/note.xml');
        $this->assertInternalType('array', $this->xmlValidator->validateDom($xml, __DIR__ . '/schema.xsd'));
    }

    public function testValidateDomWithInvalidXsd()
    {
        $xml = new DOMDocument();
        $xml->load(__DIR__ . '/note.xml');
        $result = $this->xmlValidator->validateDom($xml, __DIR__ . '/invalid_schema.xsd');

        $this->assertInternalType('array', $result);
        $this->assertSame(2, $result[0]->level);
    }

    public function testEnableXsdCache()
    {
        $path = vfsStream::url('root/cache');

        $this->xmlValidator->enableXsdCache($path);
        $result = $this->xmlValidator->validateFile(__DIR__ . '/external.xml', __DIR__ . '/external.xsd');

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);

        $result = $this->xmlValidator->validateFile(__DIR__ . '/external.xml', __DIR__ . '/external.xsd');
        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);
    }
}
