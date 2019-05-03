<?php

namespace Selective\Xml;

use DOMDocument;
use RuntimeException;

/**
 * Xml validator.
 */
class XmlValidator
{
    /**
     * Validate XML-File against XSD-File (Schema).
     *
     * @param string $xmlFile xml filename
     * @param string $xsdFile xsd filename
     *
     * @throws RuntimeException
     *
     * @return XmlValidationResult Xml validation result
     */
    public function validateFile(string $xmlFile, string $xsdFile): XmlValidationResult
    {
        $content = file_get_contents($xmlFile);
        if ($content === false) {
            throw new RuntimeException(sprintf('File could no be read: %s', $xmlFile));
        }

        return $this->validateXml($content, $xsdFile);
    }

    /**
     * Validate XML-File against XSD-File (Schema).
     *
     * @param DOMDocument $dom xml document
     * @param string $xsdFile A string containing the schema
     *
     * @return XmlValidationResult Xml validation result
     */
    public function validateDom(DOMDocument $dom, string $xsdFile): XmlValidationResult
    {
        // Workaround for complex schemas with namespace
        // Fixed: No matching global declaration available for
        // the validation root error
        $dom->loadXML($dom->saveXML());

        return $this->validateXml($dom->saveXML(), $xsdFile);
    }

    /**
     * Validate xml content against XSD file.
     *
     * @param string $xmlContent XML content
     * @param string $xsdFile XSD filename
     *
     * @return XmlValidationResult Xml validation result
     */
    public function validateXml(string $xmlContent, string $xsdFile): XmlValidationResult
    {
        $result = new XmlValidationResult();

        // Enable user error handling
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $xml = new DOMDocument();
        $wellFormed = $xml->loadXML($xmlContent);

        if (!$wellFormed) {
            throw new RuntimeException(sprintf('XML content is not well formed'));
        }

        if (!$xml->schemaValidate($xsdFile)) {
            $result = $this->getValidationErrors($xmlContent);
        }

        return $result;
    }

    /**
     * Get validation errors.
     *
     * @param string $content The xml content
     *
     * @return XmlValidationResult Error details
     */
    private function getValidationErrors(string $content): XmlValidationResult
    {
        $xmlLines = explode("\n", $content);
        $errors = libxml_get_errors();

        $result = new XmlValidationResult();

        foreach ($errors as $error) {
            $validationError = new XmlValidationError();

            $validationError->level = $error->level;
            $validationError->message = trim($error->message);
            $validationError->file = str_replace('file:///', '', $error->file);
            $validationError->line = $error->line;
            $validationError->content = '';
            $validationError->code = $error->code;
            $validationError->column = $error->column;

            if (isset($xmlLines[$error->line - 1])) {
                $validationError->content = trim($xmlLines[$error->line - 1]);
            }

            $result = $result->withError($validationError);
        }

        return $result;
    }

    /**
     * Speeding up XML schema validations of a batch of
     * XML files against the same XML schema (XSD) (13865149).
     *
     * @param string $path The temporary cache path
     * @param int $chmod chmod
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function enableXsdCache(string $path, $chmod = 0775): void
    {
        libxml_set_external_entity_loader(static function ($public, $system, $context) use ($path, $chmod) {
            if (is_file($system)) {
                echo $system;

                return $system;
            }

            if (!file_exists($path)) {
                if (!mkdir($path, $chmod, true) && !is_dir($path)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
                }
                chmod($path, $chmod);
            }

            $cachedFile = $path . '/' . sha1($system) . '_' . basename($system);
            if (is_file($cachedFile)) {
                return $cachedFile;
            }

            // Download XSD file from web
            $system = str_replace('file:/', '', $system);
            $content = file_get_contents($system);
            file_put_contents($cachedFile, $content);
            chmod($cachedFile, $chmod);

            return $cachedFile;
        });
    }
}
