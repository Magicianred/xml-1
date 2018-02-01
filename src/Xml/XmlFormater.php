<?php

namespace Odan\Xml;

use DOMDocument;

/**
 * XmlFormater utils
 *
 * @copyright 2016 odan
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */
class XmlFormater
{

    /**
     * XML beautifier
     *
     * @param string $content
     * @return string pretty xml string
     */
    public function formatString($content)
    {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->loadXML($content);
        $result = $xml->saveXML();
        return $result;
    }

    /**
     * File XML Beautifier
     *
     * @param string $fileName
     * @param string $fileNameDestination
     * @return boolean
     */
    public function formatFile($fileName, $fileNameDestination = null)
    {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->load($fileName);
        if ($fileNameDestination === null) {
            $fileNameDestination = $fileName;
        }
        $result = ($xml->save($fileNameDestination) !== false);
        return $result;
    }
}
