<?php

namespace Selective\Xml;

use DOMDocument;
use RuntimeException;

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
     * $success
     *
     * @return DOMDocument The formated DOMDocument
     */
    public function formatString(string $content): DOMDocument
    {
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $success = $xml->loadXML($content);

        if (!$success) {
            throw new RuntimeException(sprintf('XML content is not well formed'));
        }

        return $xml;
    }

    /**
     * XML file beautifier.
     *
     * @param string $fileName The xml filename
     *
     * @return DOMDocument The formated DOMDocument
     */
    public function formatFile(string $fileName): DOMDocument
    {
        $content = file_get_contents($fileName);
        if ($content === false) {
            throw new RuntimeException(sprintf('File could no be read: %s', $fileName));
        }

        $xml = new DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $success = $xml->loadXML($content);

        if (!$success) {
            throw new RuntimeException(sprintf('XML content is not well formed'));
        }

        return $xml;
    }
}
