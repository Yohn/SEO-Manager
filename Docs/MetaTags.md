# MetaTags Class

Manages HTML meta tags with built-in validation and character limits.

## Constants

```php
private const TITLE_MAX_LENGTH = 60;
private const DESCRIPTION_MIN_LENGTH = 155;
private const DESCRIPTION_MAX_LENGTH = 160;
private const KEYWORDS_MAX_COUNT = 10;
```

## Constructor

```php
public function __construct(array $config = [])
```

### Example
```php
$config = [
    'defaults' => [
        'viewport' => 'width=device-width, initial-scale=1',
        'robots' => 'index, follow'
    ]
];
$metaTags = new MetaTags($config);
```

## Methods

### setTitle()
Sets the page title with automatic truncation at 60 characters.

```php
public function setTitle(string $title): self
```

#### Examples
```php
// Simple title
$metaTags->setTitle('Welcome to Our Website');

// Long title (auto-truncated)
$metaTags->setTitle('This is a very long title that will be automatically truncated at 60 characters');
// Result: "This is a very long title that will be automatically trunca..."
```

### setDescription()
Sets meta description with length validation (155-160 characters recommended).

```php
public function setDescription(string $description): self
```

#### Examples
```php
// Optimal length
$metaTags->setDescription('Discover amazing products and services that will transform your business. We offer innovative solutions tailored to your needs with exceptional customer support.');

// Too long (auto-truncated)
$metaTags->setDescription('This description is way too long and will be truncated. ' . str_repeat('Extra content. ', 20));
// Result: Truncated at 157 characters + "..."
```

### setKeywords()
Sets meta keywords with normalization and limit.

```php
public function setKeywords(array|string $keywords): self
```

#### Features
- Accepts array or comma-separated string
- Converts to lowercase
- Removes duplicates
- Limits to 10 keywords

#### Examples
```php
// Array format
$metaTags->setKeywords(['SEO', 'optimization', 'website', 'SEO']);
// Result: "seo, optimization, website"

// String format
$metaTags->setKeywords('PHP, development, PHP, Web Development');
// Result: "php, development, web development"

// With hyphens and multi-word
$metaTags->setKeywords(['to-do app', 'todo app', 'task manager']);
// Result: "to-do app, todo app, task manager"
```

### setCanonical()
Sets the canonical URL.

```php
public function setCanonical(string $url): self
```

#### Example
```php
$metaTags->setCanonical('https://example.com/products/widget');
```

### setRobots()
Sets robots meta tag directives.

```php
public function setRobots(string $robots): self
```

#### Examples
```php
// Allow indexing
$metaTags->setRobots('index, follow');

// Prevent indexing
$metaTags->setRobots('noindex, nofollow');

// Advanced directives
$metaTags->setRobots('index, follow, max-snippet:160, max-image-preview:large');
```

### setViewport()
Sets viewport meta tag for responsive design.

```php
public function setViewport(string $viewport = 'width=device-width, initial-scale=1'): self
```

#### Examples
```php
// Default responsive
$metaTags->setViewport();

// Custom viewport
$metaTags->setViewport('width=device-width, initial-scale=1, maximum-scale=5');
```

### setFavicon()
Sets the favicon link.

```php
public function setFavicon(string $url): self
```

#### Example
```php
$metaTags->setFavicon('/favicon.ico');
```

### setCustom()
Adds custom meta tags.

```php
public function setCustom(string $name, string $content): self
```

#### Examples
```php
// Author meta tag
$metaTags->setCustom('author', 'John Smith');

// Custom application tags
$metaTags->setCustom('application-name', 'My App');
$metaTags->setCustom('theme-color', '#4285f4');

// Verification tags
$metaTags->setCustom('google-site-verification', 'verification-code');
```

### validate()
Validates all meta tags and returns array of errors.

```php
public function validate(): array
```

#### Example
```php
$errors = $metaTags->validate();
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "Validation Error: $error\n";
    }
}
```

### render()
Renders meta tags as HTML.

```php
public function render(): string
```

#### Example Output
```html
<title>Welcome to Our Website</title>
<meta name="description" content="Discover amazing products and services that will transform your business.">
<meta name="keywords" content="business, services, products">
<meta name="robots" content="index, follow">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="canonical" href="https://example.com/page">
<link rel="icon" href="/favicon.ico">
<meta name="author" content="John Smith">
```

### toArray()
Returns all meta tags as an array.

```php
public function toArray(): array
```

## Complete Examples

### Basic Website Page
```php
$metaTags = new MetaTags();

$metaTags
    ->setTitle('Home - My Business')
    ->setDescription('Welcome to My Business. We provide exceptional services and products to help your company grow.')
    ->setKeywords(['business', 'services', 'products', 'consulting'])
    ->setCanonical('https://mybusiness.com')
    ->setFavicon('/favicon.ico');

echo $metaTags->render();
```

### Blog Post
```php
$metaTags = new MetaTags();

$postTitle = '10 SEO Tips for 2024';
$postExcerpt = 'Learn the most effective SEO strategies for 2024. From technical optimization to content strategy, discover what works.';

$metaTags
    ->setTitle($postTitle . ' | Tech Blog')
    ->setDescription($postExcerpt)
    ->setKeywords(['seo tips', '2024', 'search optimization', 'google ranking'])
    ->setCanonical('https://blog.example.com/seo-tips-2024')
    ->setCustom('author', 'Jane Doe')
    ->setCustom('article:published_time', '2024-01-15');

// Validate before rendering
$errors = $metaTags->validate();
if (empty($errors)) {
    echo $metaTags->render();
}
```

### E-commerce Product
```php
$metaTags = new MetaTags();

$product = [
    'name' => 'Wireless Bluetooth Headphones',
    'brand' => 'AudioPro',
    'price' => '$79.99'
];

$metaTags
    ->setTitle($product['name'] . ' - ' . $product['brand'] . ' | Shop')
    ->setDescription('Buy ' . $product['name'] . ' at ' . $product['price'] . '. Premium sound quality with 30-hour battery life. Free shipping on orders over $50.')
    ->setKeywords([
        'bluetooth headphones',
        'wireless headphones',
        strtolower($product['brand']),
        'audio accessories'
    ])
    ->setCanonical('https://shop.example.com/headphones/wireless-bluetooth')
    ->setCustom('product:price:amount', '79.99')
    ->setCustom('product:price:currency', 'USD');

echo $metaTags->render();
```

### Landing Page with A/B Testing
```php
$metaTags = new MetaTags();

// A/B test different titles
$variant = $_GET['variant'] ?? 'a';
$titles = [
    'a' => 'Get Started with Our Platform Today',
    'b' => 'Transform Your Business - Start Free Trial'
];

$metaTags
    ->setTitle($titles[$variant])
    ->setDescription('Join thousands of businesses using our platform. Start your free 14-day trial with no credit card required.')
    ->setKeywords(['saas platform', 'business software', 'free trial'])
    ->setRobots('index, follow, max-snippet:160')
    ->setCustom('variant', $variant)
    ->setCustom('experiment', 'landing-page-title');

echo $metaTags->render();
```

### Multi-language Support
```php
$metaTags = new MetaTags();

$lang = $_GET['lang'] ?? 'en';
$translations = [
    'en' => [
        'title' => 'Welcome to Our Store',
        'description' => 'Shop the latest products with free worldwide shipping.',
        'keywords' => ['online shop', 'free shipping', 'products']
    ],
    'es' => [
        'title' => 'Bienvenido a Nuestra Tienda',
        'description' => 'Compra los últimos productos con envío gratis mundial.',
        'keywords' => ['tienda en línea', 'envío gratis', 'productos']
    ]
];

$t = $translations[$lang];

$metaTags
    ->setTitle($t['title'])
    ->setDescription($t['description'])
    ->setKeywords($t['keywords'])
    ->setCustom('language', $lang)
    ->setCustom('alternate', 'https://example.com?lang=' . ($lang === 'en' ? 'es' : 'en'));

echo $metaTags->render();
```

## Best Practices

1. **Title Length**: Keep titles under 60 characters for full display in SERPs
2. **Description Length**: Aim for 155-160 characters for optimal display
3. **Keywords**: Focus on 5-10 relevant keywords, avoid keyword stuffing
4. **Canonical URLs**: Always use absolute URLs
5. **Custom Tags**: Use for platform-specific needs (social media, apps, verification)

## Validation Rules

- Title: Maximum 60 characters
- Description: 155-160 characters recommended
- Keywords: Maximum 10 keywords
- All values are HTML-escaped on render