<?php

namespace Molengo;

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
}
