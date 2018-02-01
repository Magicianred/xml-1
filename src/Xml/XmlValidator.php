<?php

namespace Odan\Xml;

/**
 * XmlValidation utils
 *
 * @copyright 2016 odan
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
class XmlValidator
{

    /**
     * Validate XML-File against XSD-File (Schema)
     *
     * @param string $xmlFile
     * @param string $xsdFile
     * @return array if not valid an array with errors
     */
    public function validateFile($xmlFile, $xsdFile)
    {
        $results = array();

        // Enable user error handling
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $xml = new \DOMDocument();
        $xml->load($xmlFile);
        if (!$xml->schemaValidate($xsdFile)) {
            // Not valid
            $xmlLines = explode("\n", file_get_contents($xmlFile));
            $i = 0;
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $results[$i]['level'] = $error->level;
                $results[$i]['message'] = trim($error->message);
                $results[$i]['file'] = str_replace('file:///', '', $error->file);
                $results[$i]['line'] = $error->line;
                $results[$i]['content'] = '';
                if (isset($xmlLines[$error->line - 1])) {
                    $results[$i]['content'] = trim($xmlLines[$error->line - 1]);
                }
                $results[$i]['code'] = $error->code;
                $results[$i]['column'] = $error->column;
                $i++;
            }
        }
        libxml_clear_errors();
        return $results;
    }

    /**
     * Validate XML-File against XSD-File (Schema)
     *
     * @param \DOMDocument $xml xml document
     * @param string $xsdSource A string containing the schema
     * @return array if not valid an array with errors
     */
    public function validateDom(\DOMDocument $xml, $xsdSource)
    {
        $results = array();

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
                $result = array();
                $result['level'] = $error->level;
                $result['message'] = trim($error->message);
                $result['file'] = str_replace('file:///', '', $error->file);
                $result['line'] = $error->line;
                $result['code'] = $error->code;
                $result['column'] = $error->column;
                $results[] = $result;
            }
        }
        libxml_clear_errors();
        return $results;
    }

    /**
     * Speeding up XML schema validations of a batch of
     * XML files against the same XML schema (XSD) (13865149)
     *
     * @return void
     */
    public function enableXsdCache()
    {
        libxml_set_external_entity_loader(function ($public, $system, $context) {
            if (is_file($system)) {
                return $system;
            }
            $cachedFile = TMP . 'xsd';
            if (!file_exists($cachedFile)) {
                mkdir($cachedFile, true);
                chmod($cachedFile, 0775);
            }
            $cachedFile .= '/' . sha1($system) . '_' . basename($system);
            if (is_file($cachedFile)) {
                return $cachedFile;
            }
            // Download XSD file from web
            $content = file_get_contents($system);
            file_put_contents($cachedFile, $content);
            chmod($cachedFile, 0775);
            return $cachedFile;
        });
    }
}
