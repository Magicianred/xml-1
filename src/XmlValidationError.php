<?php

namespace Selective\Xml;

/**
 * Validation error.
 */
final class XmlValidationError
{
    /**
     * @var int
     */
    public $level;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $file;

    /**
     * @var int
     */
    public $line;

    /**
     * @var string
     */
    public $content;

    /**
     * @var int
     */
    public $code;

    /**
     * @var int
     */
    public $column;
}
