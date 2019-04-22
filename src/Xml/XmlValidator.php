<?php

namespace Selective\Xml;

use DOMDocument;

/**
 * Xml validator
 */
class XmlValidator
{

    /**
     * Validate XML-File against XSD-File (Schema)
     *
     * @param string $xmlFile
     * @param string $xsdFile
     * @return XmlValidationResult[] if not valid an array with errors
     */
    public function validateFile(string $xmlFile, string $xsdFile): array
    {
        $result = [];

        // Enable user error handling
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $xml = new DOMDocument();
        $xml->load($xmlFile);
        if (!$xml->schemaValidate($xsdFile)) {
            // Not valid

            $xmlLines = explode("\n", file_get_contents($xmlFile));
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $validation = new XmlValidationResult();
                $validation->level = $error->level;
                $validation->message = trim($error->message);
                $validation->file = str_replace('file:///', '', $error->file);
                $validation->line = $error->line;
                $validation->content = '';

                if (isset($xmlLines[$error->line - 1])) {
                    $validation->content = trim($xmlLines[$error->line - 1]);
                }
                $validation->code = $error->code;
                $validation->column = $error->column;

                $result[] = $validation;
            }
        }
        libxml_clear_errors();

        return $result;
    }

    /**
     * Validate XML-File against XSD-File (Schema)
     *
     * @param DOMDocument $xml xml document
     * @param string $xsdSource A string containing the schema
     * @return XmlValidationResult[] if not valid an array with errors
     */
    public function validateDom(DOMDocument $xml, string $xsdSource): array
    {
        $result = array();

        // Enable user error handling
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        // Workaround for complex schemas with namespace
        // Fixed: No matching global declaration available for
        // the validation root error
        $xml->loadXML($xml->saveXML());

        if (!$xml->schemaValidate($xsdSource)) {
            // Not valid
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $validation = new XmlValidationResult();

                $validation->level = $error->level;
                $validation->message = trim($error->message);
                $validation->file = str_replace('file:///', '', $error->file);
                $validation->line = $error->line;
                $validation->code = $error->code;
                $validation->column = $error->column;
                $result[] = $validation;
            }
        }
        libxml_clear_errors();

        return $result;
    }

    /**
     * Speeding up XML schema validations of a batch of
     * XML files against the same XML schema (XSD) (13865149)
     *
     * @param string $path The temporary cache path
     * @return void
     */
    public function enableXsdCache(string $path)
    {
        libxml_set_external_entity_loader(function ($public, $system, $context) use ($path) {
            if (is_file($system)) {
                echo $system;
                return $system;
            }

            if (!file_exists($path)) {
                mkdir($path, true);
                chmod($path, 0775);
            }

            $cachedFile = $path . '/' . sha1($system) . '_' . basename($system);
            if (is_file($cachedFile)) {
                return $cachedFile;
            }

            // Download XSD file from web
            $system = str_replace('file:/', '', $system);
            $content = file_get_contents($system);
            file_put_contents($cachedFile, $content);
            chmod($cachedFile, 0775);

            return $cachedFile;
        });
    }
}
