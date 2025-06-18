# SEOManager Class

The main orchestrator class that manages all SEO components.

## Constructor

```php
public function __construct(?Config $config = null)
```

### Parameters
- `$config` - Optional Config instance for loading defaults

### Example
```php
use Yohns\Core\Config;
use Yohns\SEO\SEOManager;

$config = new Config(__DIR__ . '/config');
$seo = new SEOManager($config);
```

## Methods

### setCommon()
Sets common properties across all SEO components.

```php
public function setCommon(array $data): self
```

#### Supported Properties
- `title` - Sets title for meta, Open Graph, and Twitter
- `description` - Sets description for meta, Open Graph, and Twitter
- `image` - Sets image for Open Graph and Twitter
- `url` - Sets canonical URL and Open Graph URL

#### Example
```php
$seo->setCommon([
    'title' => 'Product Page - Amazing Widget',
    'description' => 'The best widget for all your needs',
    'image' => 'https://example.com/widget.jpg',
    'url' => 'https://example.com/products/widget'
]);
```

### meta()
Returns the MetaTags instance.

```php
public function meta(): MetaTags
```

#### Example
```php
$seo->meta()
    ->setKeywords(['widget', 'product', 'amazing'])
    ->setRobots('index, follow');
```

### openGraph()
Returns the OpenGraph instance.

```php
public function openGraph(): OpenGraph
```

#### Example
```php
$seo->openGraph()
    ->setType('product')
    ->setSiteName('My Store');
```

### twitter()
Returns the TwitterCards instance.

```php
public function twitter(): TwitterCards
```

#### Example
```php
$seo->twitter()
    ->setCard('summary_large_image')
    ->setCreator('@creator');
```

### schema()
Returns the SchemaMarkup instance.

```php
public function schema(): SchemaMarkup
```

#### Example
```php
$productSchema = $seo->schema()->product([
    'name' => 'Amazing Widget',
    'price' => '99.99',
    'brand' => 'WidgetCo'
]);
$seo->schema()->add($productSchema);
```

### render()
Renders all SEO tags as HTML.

```php
public function render(): string
```

#### Example
```php
// In your HTML head
echo $seo->render();
```

### toArray()
Returns all SEO data as an array.

```php
public function toArray(): array
```

#### Example
```php
$data = $seo->toArray();
// Returns:
// [
//     'meta' => [...],
//     'opengraph' => [...],
//     'twitter' => [...],
//     'schema' => [...]
// ]
```

## Complete Examples

### Blog Post Page
```php
$seo = new SEOManager($config);

// Set common elements
$seo->setCommon([
    'title' => 'How to Use SEO Manager - Complete Guide',
    'description' => 'Learn how to implement comprehensive SEO with our manager.',
    'image' => 'https://blog.example.com/seo-guide.jpg',
    'url' => 'https://blog.example.com/seo-manager-guide'
]);

// Additional meta settings
$seo->meta()
    ->setKeywords(['seo', 'tutorial', 'guide', 'php'])
    ->setCanonical('https://blog.example.com/seo-manager-guide');

// Open Graph for articles
$seo->openGraph()
    ->setType('article')
    ->setArticle([
        'author' => 'John Smith',
        'published_time' => '2024-01-15T10:00:00Z',
        'section' => 'Tutorials',
        'tag' => ['seo', 'php', 'web development']
    ]);

// Twitter settings
$seo->twitter()
    ->setCard('summary_large_image')
    ->setCreator('@johnsmith');

// Add article schema
$articleSchema = $seo->schema()->article([
    'headline' => 'How to Use SEO Manager - Complete Guide',
    'description' => 'Learn how to implement comprehensive SEO with our manager.',
    'image' => 'https://blog.example.com/seo-guide.jpg',
    'datePublished' => '2024-01-15T10:00:00Z',
    'author' => [
        'name' => 'John Smith',
        'url' => 'https://blog.example.com/authors/john-smith'
    ],
    'publisher' => [
        'name' => 'Tech Blog',
        'logo' => 'https://blog.example.com/logo.png'
    ]
]);
$seo->schema()->add($articleSchema);

// Render in HTML
echo $seo->render();
```

### E-commerce Product Page
```php
$seo = new SEOManager($config);

$product = [
    'name' => 'Professional DSLR Camera',
    'description' => 'Capture stunning photos with our professional DSLR camera.',
    'price' => '1299.99',
    'image' => 'https://shop.example.com/cameras/dslr-pro.jpg',
    'brand' => 'CameraPro',
    'model' => 'CP-2024',
    'sku' => 'CAM-DSLR-001'
];

// Set all common properties
$seo->setCommon([
    'title' => $product['name'] . ' - Buy Online',
    'description' => $product['description'],
    'image' => $product['image'],
    'url' => 'https://shop.example.com/cameras/dslr-pro'
]);

// E-commerce specific meta
$seo->meta()
    ->setKeywords(['dslr camera', 'professional camera', $product['brand']]);

// Product Open Graph
$seo->openGraph()
    ->setType('product');

// Product schema with review
$productSchema = $seo->schema()->product([
    'name' => $product['name'],
    'image' => $product['image'],
    'description' => $product['description'],
    'brand' => $product['brand'],
    'model' => $product['model'],
    'price' => $product['price'],
    'currency' => 'USD',
    'review' => [
        'reviewBody' => 'Best camera I have ever owned!',
        'ratingValue' => '5',
        'author' => 'Jane Photographer'
    ]
]);
$seo->schema()->add($productSchema);

// Add breadcrumb schema
$breadcrumbSchema = $seo->schema()->create('BreadcrumbList', [
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => 'https://shop.example.com'
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Cameras',
            'item' => 'https://shop.example.com/cameras'
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $product['name']
        ]
    ]
]);
$seo->schema()->add($breadcrumbSchema);

echo $seo->render();
```

### Local Business Page
```php
$seo = new SEOManager($config);

$business = [
    'name' => 'Joe\'s Pizza Restaurant',
    'description' => 'Authentic Italian pizza made fresh daily since 1985.',
    'phone' => '+1-555-123-4567',
    'image' => 'https://joespizza.com/storefront.jpg'
];

$seo->setCommon([
    'title' => $business['name'] . ' - Best Pizza in Town',
    'description' => $business['description'],
    'image' => $business['image'],
    'url' => 'https://joespizza.com'
]);

// Local business schema
$businessSchema = $seo->schema()->localBusiness([
    'name' => $business['name'],
    'image' => $business['image'],
    'description' => $business['description'],
    'telephone' => $business['phone'],
    'address' => [
        'streetAddress' => '123 Main Street',
        'addressLocality' => 'New York',
        'addressRegion' => 'NY',
        'postalCode' => '10001',
        'addressCountry' => 'US'
    ],
    'priceRange' => '$$',
    'openingHours' => [
        'Mo-Fr 11:00-22:00',
        'Sa-Su 12:00-23:00'
    ]
]);
$seo->schema()->add($businessSchema);

// Organization schema
$orgSchema = $seo->schema()->organization([
    'name' => $business['name'],
    'url' => 'https://joespizza.com',
    'logo' => 'https://joespizza.com/logo.png',
    'telephone' => $business['phone'],
    'email' => 'info@joespizza.com'
]);
$seo->schema()->add($orgSchema);

echo $seo->render();
```

## Validation

Always validate before rendering:

```php
// Get validation errors from each component
$errors = array_merge(
    $seo->meta()->validate(),
    $seo->openGraph()->validate(),
    $seo->twitter()->validate()
);

if (!empty($errors)) {
    // Handle errors
    foreach ($errors as $error) {
        error_log("SEO Validation Error: " . $error);
    }
}

// Render even with errors (non-critical)
echo $seo->render();
```

## API/JSON Output

For headless applications or APIs:

```php
$seo = new SEOManager($config);

// Configure SEO...
$seo->setCommon([...]);

// Get as array
$seoData = $seo->toArray();

// Return as JSON
header('Content-Type: application/json');
echo json_encode($seoData);
```