# Yohns\SEO

```cli
composer require yohns/seo-manager
```
> Not everythings been tested

## Documentation

- [SEOManager Class](Docs/SEOManager.md) - Main orchestrator class
- [Favicons Class](Docs/Favicons.md) - Favicon tags management
- [MetaTags Class](Docs/MetaTags.md) - HTML meta tags management
- [OpenGraph Class](Docs/OpenGraph.md) - Open Graph protocol
- [TwitterCards Class](Docs/TwitterCards.md) - Twitter Card tags
- [SchemaMarkup Class](Docs/SchemaMarkup.md) - Schema.org structured data
- [Configuration](Docs/Configuration.md) - Setting up defaults
- [Examples](Docs/Examples.md) - Common usage patterns

## Main Components:

### 1. **SEOManager** (Main Class)
- Central orchestrator for all SEO components
- Supports configuration via your Config class
- Provides unified access to all SEO features
- Can render all tags at once or individually

### 2. **MetaTags** Class
- Handles standard HTML meta tags
- Built-in validation for title length (60 chars) and description (155-160 chars)
- Supports keywords with normalization (lowercase, deduplication)
- Manages canonical URLs, robots, viewport, favicon

### 3. **OpenGraph** Class
- Full Open Graph protocol support
- Validates required tags (title, type, url, image)
- Supports all standard OG types
- Special handlers for articles, videos, audio

### 4. **TwitterCards** Class
- Supports all Twitter Card types (summary, summary_large_image, app, player)
- Automatic username normalization (adds @ if missing)
- Character limit enforcement (70 for title, 200 for description)
- Type-specific validation

### 5. **SchemaMarkup** Class
- Comprehensive Schema.org support
- Helper methods for all common types:
  - Organization, Person, Product, Article
  - Event, Recipe, Review, Video
  - Book, Course, LocalBusiness, FAQPage
  - SoftwareApplication, and more
- Generic schema builder for custom types
- JSON-LD output format

## Key Features:

1. **Validation** - Each component validates its tags according to best practices
2. **Configuration Support** - Works with your Config class for defaults
3. **Fluent Interface** - Chainable methods for easy use
4. **Flexible Output** - Can render as HTML strings or return as arrays
5. **Character Limits** - Automatic truncation with ellipsis for length-limited fields
6. **Best Practices** - Follows all SEO guidelines from your documentation

## Usage Pattern:
```php
$seo = new SEOManager($config);
$seo->setCommon([...])  // Set common properties across all components
    ->meta()->setTitle('...')
    ->openGraph()->setType('article')
    ->twitter()->setCard('summary_large_image');

echo $seo->render();  // Output all tags
```

The system is fully OOP, uses PHP 8.2+ features, and follows all the SEO best practices outlined in your documentation files.

## TO-DO
- `Yohns\CoreConfig` to be used statically `Config::*()`
- Test everything