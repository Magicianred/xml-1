<?php

namespace Selective\Xml;

/**
 * Validation result.
 */
final class XmlValidationResult
{
    /**
     * @var XmlValidationError[] errors
     */
    private $errors = [];

    /**
     * Add error.
     *
     * @param XmlValidationError $error The error
     *
     * @return self The result
     */
    public function withError(XmlValidationError $error): self
    {
        $clone = clone $this;
        $clone->errors[] = clone $error;

        return $clone;
    }

    public function getErrors(): array
    {
        $clone = clone $this;

        return $clone->errors;
    }

    public function clear(): self
    {
        $clone = clone $this;
        $clone->errors = [];

        return $clone;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function isInvalid(): bool
    {
        return !empty($this->errors);
    }

    private function __clone()
    {
        foreach ($this->errors as $key => $value) {
            $this->errors[$key] = clone $value;
        }
    }
}
