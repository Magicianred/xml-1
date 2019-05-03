<?php

namespace Selective\Xml;

use DOMDocument;

/**
 * XmlFormater.
 */
class XmlFormater
{
    /**
     * XML beautifier.
     *
     * @param string $content
     *
     * @return string pretty XML string
     */
    public function formatString(string $content): string
    {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->loadXML($content);
        $result = $xml->saveXML();

        return $result;
    }

    /**
     * File XML Beautifier.
     *
     * @param string $fileName
     * @param string $fileNameDestination
     *
     * @return bool Success
     */
    public function formatFile($fileName, $fileNameDestination = null): bool
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
