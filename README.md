# Xml validator and beautifier

[![Latest Version on Packagist](https://img.shields.io/github/release/selective-php/xml.svg)](https://packagist.org/packages/selective/xml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://travis-ci.org/selective-php/xml.svg?branch=master)](https://travis-ci.org/selective-php/xml)
[![Coverage Status](https://scrutinizer-ci.com/g/selective-php/xml/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/selective-php/xml/code-structure)
[![Quality Score](https://scrutinizer-ci.com/g/selective-php/xml/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/selective-php/xml/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/selective/xml.svg)](https://packagist.org/packages/selective/xml/stats)


## Installation

```shell
composer require selective/xml
```

## Requirements

* PHP >= 7.1

## Usage

Validating an xml file against an xsd schema:

```php
use Selective\Xml\XmlValidator;

$xmlValidator = new XmlValidator();
$xmlValidationResult = $xmlValidator->validateFile('file.xml', 'schema.xsd');

if ($xmlValidationResult->isValid()) {
    echo 'XML validation successful';
} else {
    var_export($xmlValidationResult->getErrors());
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
