# SEO Manager for PHP 8.2+

A comprehensive SEO management system that handles meta tags, Open Graph, Twitter Cards, and Schema.org structured data.

## Installation

```bash
composer require yohns/seo-manager
```

## Quick Start

```php
use Yohns\Core\Config;
use Yohns\SEO\SEOManager;

// Initialize with configuration
$config = new Config(__DIR__ . '/config');
$seo = new SEOManager($config);

// Set common properties
$seo->setCommon([
    'title' => 'My Page Title',
    'description' => 'Page description here',
    'image' => 'https://example.com/image.jpg',
    'url' => 'https://example.com/page'
]);

// Render all tags
echo $seo->render();
```

## Features

- **Meta Tags Management** - Title, description, keywords with validation
- **Open Graph Protocol** - Full support for all OG types
- **Twitter Cards** - All card types with character limits
- **Schema.org Markup** - Comprehensive structured data support
- **Configuration Support** - Default values via Config class
- **Validation** - Built-in validation for all tag types
- **Fluent Interface** - Chainable methods

## Documentation

- [SEOManager Class](SEOManager.md) - Main orchestrator class
- [Favicons Class](Favicons.md) - Favicon tags management
- [MetaTags Class](MetaTags.md) - HTML meta tags management
- [OpenGraph Class](OpenGraph.md) - Open Graph protocol
- [TwitterCards Class](TwitterCards.md) - Twitter Card tags
- [SchemaMarkup Class](SchemaMarkup.md) - Schema.org structured data
- [Configuration](Configuration.md) - Setting up defaults
- [Examples](Examples.md) - Common usage patterns

## Requirements

- PHP 8.2 or higher
- Yohns\Core\Config (optional, for configuration support)

## License

MIT