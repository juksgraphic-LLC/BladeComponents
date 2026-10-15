# Juksgraphic Blade Components

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juksgraphic/bladecomponents.svg?style=flat-square)](https://packagist.org/packages/juksgraphic/bladecomponents)
[![Total Downloads](https://img.shields.io/packagist/dt/juksgraphic/bladecomponents.svg?style=flat-square)](https://packagist.org/packages/juksgraphic/bladecomponents)
[![License](https://img.shields.io/packagist/l/juksgraphic/bladecomponents.svg?style=flat-square)](LICENSE.md)

**Juksgraphic Blade Components** is a robust, cross-framework UI component library built with Tailwind CSS, Alpine.js, and Penguin UI principles. It is engineered to work seamlessly in native Laravel applications or lightweight standalone PHP projects using BladeOne.

## Installation

You can install the package via Composer:


```bash
composer require juksgraphic/bladecomponents
```

## Usage

You can easily register and render components in your Blade templates or standalone PHP views using BladeOne. Use the package helper class to retrieve paths and resources:

```php
use Juksgraphic\BladeComponents\BladeComponents;

// Retrieve views/components directory path
$viewsPath = BladeComponents::path();

// Retrieve theme CSS file path
$themePath = BladeComponents::theme();

// Get all available component names
$components = BladeComponents::all();
```

### Rendering a Component Example (Laravel / BladeOne)

```html
@include('jkc.feedbacks.alert', [
    'type' => 'success',
    'style' => 'left-border',
    'title' => 'System Operational',
    'message' => 'All components are successfully loaded.'
])
```

## Testing

The package includes comprehensive test suites powered by Pest PHP:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/juksgraphic-LLC/BladeComponents/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review our [security policy](https://github.com/juksgraphic-LLC/BladeComponents/security/policy) on how to report security vulnerabilities.

## Credits

- [Kingsley Guillaume](https://github.com/juksgraphic-LLC)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.