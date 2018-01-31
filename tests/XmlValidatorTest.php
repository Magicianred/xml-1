<?php

namespace Odan\Test;

use Odan\Xml\XmlValidator;
use PHPUnit\Framework\TestCase;

class XmlValidatorTest extends TestCase
{
    protected $xmlValidater;

    protected function setUp()
    {
        $this->xmlValidator = new XmlValidator();
    }

    protected function tearDown()
    {
        $this->xmlValidator = null;
    }

    public function testValidateFile()
    {
        $this->assertInternalType('array', $this->xmlValidator->validateFile(__DIR__.'/note.xml', __DIR__.'/schema.xsd'));
    }

    public function testValidateFileWithInvalidXsd()
    {
        $result = $this->xmlValidator->validateFile(__DIR__.'/note.xml', __DIR__.'/invalid_schema.xsd');
        $this->assertInternalType('array', $result);
        $this->assertSame(2, $result[0]['level']);
    }

    public function testValidateDom()
    {
        $xml = new \DomDocument();
        $xml->load(__DIR__.'/note.xml');
        $this->assertInternalType('array', $this->xmlValidator->validateDom($xml, __DIR__.'/schema.xsd'));
    }

    public function testValidateDomWithInvalidXsd()
    {
        $xml = new \DomDocument();
        $xml->load(__DIR__.'/note.xml');
        $result = $this->xmlValidator->validateDom($xml, __DIR__.'/invalid_schema.xsd');
        $this->assertInternalType('array', $result);
        $this->assertSame(2, $result[0]['level']);
    }
}
