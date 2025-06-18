# OpenGraph Class

Manages Open Graph protocol meta tags for rich social media sharing.

## Constants

```php
private const VALID_TYPES = [
    'website', 'article', 'book', 'profile', 'music.song', 'music.album',
    'music.playlist', 'music.radio_station', 'video.movie', 'video.episode',
    'video.tv_show', 'video.other'
];
```

## Constructor

```php
public function __construct(array $config = [])
```

### Example
```php
$config = [
    'defaults' => [
        'type' => 'website',
        'site_name' => 'My Website',
        'locale' => 'en_US'
    ]
];
$openGraph = new OpenGraph($config);
```

## Methods

### setTitle()
Sets the Open Graph title.

```php
public function setTitle(string $title): self
```

#### Example
```php
$openGraph->setTitle('Amazing Product - Buy Now');
```

### setType()
Sets the Open Graph type with validation.

```php
public function setType(string $type): self
```

#### Examples
```php
// Basic types
$openGraph->setType('website');
$openGraph->setType('article');
$openGraph->setType('product');

// Media types
$openGraph->setType('video.movie');
$openGraph->setType('music.song');
```

### setUrl()
Sets the canonical URL for the content.

```php
public function setUrl(string $url): self
```

#### Example
```php
$openGraph->setUrl('https://example.com/products/widget');
```

### setImage()
Sets the Open Graph image with optional dimensions and alt text.

```php
public function setImage(string $url, ?int $width = null, ?int $height = null, ?string $alt = null): self
```

#### Examples
```php
// Basic image
$openGraph->setImage('https://example.com/product.jpg');

// With dimensions and alt text
$openGraph->setImage(
    'https://example.com/hero.jpg',
    1200,  // width
    630,   // height
    'Product hero image'
);
```

### setDescription()
Sets the Open Graph description.

```php
public function setDescription(string $description): self
```

#### Example
```php
$openGraph->setDescription('Discover our amazing product that will transform your workflow.');
```

### setSiteName()
Sets the site name.

```php
public function setSiteName(string $siteName): self
```

#### Example
```php
$openGraph->setSiteName('My E-commerce Store');
```

### setLocale()
Sets the locale with optional alternates.

```php
public function setLocale(string $locale, array $alternates = []): self
```

#### Examples
```php
// Single locale
$openGraph->setLocale('en_US');

// With alternates
$openGraph->setLocale('en_US', ['es_ES', 'fr_FR', 'de_DE']);
```

### setVideo()
Sets video properties.

```php
public function setVideo(string $url, ?int $width = null, ?int $height = null, ?string $type = null): self
```

#### Example
```php
$openGraph->setVideo(
    'https://example.com/video.mp4',
    1920,  // width
    1080,  // height
    'video/mp4'
);
```

### setAudio()
Sets audio properties.

```php
public function setAudio(string $url, ?string $type = null): self
```

#### Example
```php
$openGraph->setAudio('https://example.com/podcast.mp3', 'audio/mpeg');
```

### setArticle()
Sets article-specific properties.

```php
public function setArticle(array $properties): self
```

#### Supported Properties
- `author` - Article author
- `published_time` - ISO 8601 date
- `modified_time` - ISO 8601 date
- `section` - Article section
- `tag` - Array of tags

#### Example
```php
$openGraph->setArticle([
    'author' => 'John Smith',
    'published_time' => '2024-01-15T10:00:00Z',
    'modified_time' => '2024-01-20T14:30:00Z',
    'section' => 'Technology',
    'tag' => ['php', 'seo', 'web development']
]);
```

### setCustom()
Sets custom Open Graph properties.

```php
public function setCustom(string $property, string $content): self
```

#### Example
```php
$openGraph->setCustom('music:duration', '180');
$openGraph->setCustom('book:isbn', '978-3-16-148410-0');
```

### validate()
Validates Open Graph tags.

```php
public function validate(): array
```

### render()
Renders Open Graph tags as HTML.

```php
public function render(): string
```

### toArray()
Returns Open Graph data as array.

```php
public function toArray(): array
```

## Complete Examples

### Article Page
```php
$openGraph = new OpenGraph();

$openGraph
    ->setTitle('Complete Guide to Open Graph Protocol')
    ->setType('article')
    ->setUrl('https://blog.example.com/open-graph-guide')
    ->setDescription('Learn how to implement Open Graph tags for better social media sharing.')
    ->setImage('https://blog.example.com/images/og-guide.jpg', 1200, 630)
    ->setSiteName('Tech Blog')
    ->setLocale('en_US')
    ->setArticle([
        'author' => 'Jane Developer',
        'published_time' => '2024-01-15T09:00:00Z',
        'modified_time' => '2024-01-16T10:00:00Z',
        'section' => 'Web Development',
        'tag' => ['open graph', 'seo', 'social media', 'meta tags']
    ]);

```

### Video Content
```php
$openGraph = new OpenGraph();

$video = [
    'title' => 'How to Set Up Open Graph Tags - Tutorial',
    'description' => 'Step-by-step video tutorial on implementing Open Graph tags.',
    'url' => 'https://videos.example.com/tutorials/open-graph-setup',
    'thumbnail' => 'https://videos.example.com/thumbnails/og-tutorial.jpg',
    'video_url' => 'https://videos.example.com/videos/og-tutorial.mp4',
    'duration' => 420 // 7 minutes in seconds
];

$openGraph
    ->setTitle($video['title'])
    ->setType('video.other')
    ->setUrl($video['url'])
    ->setDescription($video['description'])
    ->setImage($video['thumbnail'], 1920, 1080)
    ->setSiteName('Tutorial Videos')
    ->setVideo($video['video_url'], 1920, 1080, 'video/mp4')
    ->setCustom('video:duration', (string)$video['duration']);

echo $openGraph->render();
```

### Music Album
```php
$openGraph = new OpenGraph();

$album = [
    'title' => 'Greatest Hits Collection',
    'artist' => 'The Band',
    'release_date' => '2024-01-01',
    'cover' => 'https://music.example.com/albums/greatest-hits-cover.jpg'
];

$openGraph
    ->setTitle($album['title'] . ' by ' . $album['artist'])
    ->setType('music.album')
    ->setUrl('https://music.example.com/albums/greatest-hits')
    ->setDescription('The ultimate collection of hits from ' . $album['artist'])
    ->setImage($album['cover'], 1000, 1000)
    ->setSiteName('Music Store')
    ->setCustom('music:musician', $album['artist'])
    ->setCustom('music:release_date', $album['release_date']);

echo $openGraph->render();
```

### Multi-language Website
```php
$openGraph = new OpenGraph();

// Detect user language
$userLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
$locale = match(substr($userLang, 0, 2)) {
    'es' => 'es_ES',
    'fr' => 'fr_FR',
    'de' => 'de_DE',
    default => 'en_US'
};

$translations = [
    'en_US' => [
        'title' => 'Welcome to Our International Store',
        'description' => 'Shop worldwide with free shipping'
    ],
    'es_ES' => [
        'title' => 'Bienvenido a Nuestra Tienda Internacional',
        'description' => 'Compra en todo el mundo con envío gratis'
    ],
    'fr_FR' => [
        'title' => 'Bienvenue dans Notre Boutique Internationale',
        'description' => 'Achetez dans le monde entier avec livraison gratuite'
    ],
    'de_DE' => [
        'title' => 'Willkommen in Unserem Internationalen Shop',
        'description' => 'Weltweit einkaufen mit kostenlosem Versand'
    ]
];

$content = $translations[$locale];
$alternateLocales = array_keys($translations);
unset($alternateLocales[array_search($locale, $alternateLocales)]);

$openGraph
    ->setTitle($content['title'])
    ->setType('website')
    ->setUrl('https://shop.example.com/' . substr($locale, 0, 2))
    ->setDescription($content['description'])
    ->setImage('https://shop.example.com/images/international-hero.jpg')
    ->setSiteName('International Store')
    ->setLocale($locale, array_values($alternateLocales));

echo $openGraph->render();
```

### Book Publication
```php
$openGraph = new OpenGraph();

$book = [
    'title' => 'The Complete Guide to Web Development',
    'author' => 'Sarah Johnson',
    'isbn' => '978-0-123456-78-9',
    'publisher' => 'Tech Publishing House',
    'pages' => 450,
    'release_date' => '2024-03-15'
];

$openGraph
    ->setTitle($book['title'])
    ->setType('book')
    ->setUrl('https://books.example.com/web-development-guide')
    ->setDescription('Master modern web development with this comprehensive guide by ' . $book['author'])
    ->setImage('https://books.example.com/covers/web-dev-guide.jpg', 600, 900)
    ->setSiteName($book['publisher'])
    ->setCustom('book:author', $book['author'])
    ->setCustom('book:isbn', $book['isbn'])
    ->setCustom('book:release_date', $book['release_date']);

echo $openGraph->render();
```

### Profile Page
```php
$openGraph = new OpenGraph();

$profile = [
    'name' => 'Dr. Jane Smith',
    'title' => 'Senior Software Engineer',
    'bio' => 'Leading expert in web technologies with 15 years of experience.',
    'avatar' => 'https://profiles.example.com/jane-smith/avatar.jpg'
];

$openGraph
    ->setTitle($profile['name'] . ' - ' . $profile['title'])
    ->setType('profile')
    ->setUrl('https://profiles.example.com/jane-smith')
    ->setDescription($profile['bio'])
    ->setImage($profile['avatar'], 400, 400)
    ->setSiteName('Professional Network')
    ->setCustom('profile:first_name', 'Jane')
    ->setCustom('profile:last_name', 'Smith')
    ->setCustom('profile:username', 'janesmith');

echo $openGraph->render();
```

## Validation Examples

```php
$openGraph = new OpenGraph();

// Missing required tags
$openGraph->setTitle('My Page');
// Missing: type, url, image

$errors = $openGraph->validate();
print_r($errors);
// Output:
// Array(
//     [0] => "Required Open Graph tag missing: og:type"
//     [1] => "Required Open Graph tag missing: og:url"
//     [2] => "Required Open Graph tag missing: og:image"
// )

// Invalid type
$openGraph->setType('invalid_type');
$errors = $openGraph->validate();
// Output: ["Invalid Open Graph type: invalid_type"]
```

## Best Practices

1. **Always include required tags**: title, type, url, image
2. **Image recommendations**:
   - General: 1200x630 pixels (1.91:1 ratio)
   - Square images: 1200x1200 pixels
   - Minimum: 600x315 pixels
3. **Type selection**: Choose the most specific type for your content
4. **Locale format**: Use underscore format (en_US, not en-US)
5. **URLs**: Always use absolute URLs
6. **Multiple images**: Call setImage() multiple times for fallbacks

## Debugging Tips

1. **Facebook Sharing Debugger**: https://developers.facebook.com/tools/debug/
2. **LinkedIn Post Inspector**: https://www.linkedin.com/post-inspector/
3. **Twitter Card Validator**: https://cards-dev.twitter.com/validator

## Common Issues

1. **Images not showing**: Ensure images are publicly accessible and use HTTPS
2. **Wrong type error**: Use only valid types from VALID_TYPES constant
3. **Locale not working**: Use correct format (locale_COUNTRY)
4. **Tags not updating**: Social platforms cache OG data; use debugger tools to refresh
```

Output:
```html
<meta property="og:title" content="Complete Guide to Open Graph Protocol">
<meta property="og:type" content="article">
<meta property="og:url" content="https://blog.example.com/open-graph-guide">
<meta property="og:description" content="Learn how to implement Open Graph tags for better social media sharing.">
<meta property="og:image" content="https://blog.example.com/images/og-guide.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="Tech Blog">
<meta property="og:locale" content="en_US">
<meta property="og:article:author" content="Jane Developer">
<meta property="og:article:published_time" content="2024-01-15T09:00:00Z">
<meta property="og:article:modified_time" content="2024-01-16T10:00:00Z">
<meta property="og:article:section" content="Web Development">
<meta property="og:article:tag" content="open graph">
<meta property="og:article:tag" content="seo">
<meta property="og:article:tag" content="social media">
<meta property="og:article:tag" content="meta tags">
```

### E-commerce Product
```php
$openGraph = new OpenGraph();

$product = [
    'name' => 'Wireless Noise-Canceling Headphones',
    'description' => 'Premium headphones with active noise cancellation and 30-hour battery life.',
    'price' => '299.99',
    'currency' => 'USD',
    'image' => 'https://shop.example.com/products/headphones-black.jpg'
];

$openGraph
    ->setTitle($product['name'])
    ->setType('product')
    ->setUrl('https://shop.example.com/products/wireless-headphones')
    ->setDescription($product['description'])
    ->setImage($product['image'], 1200, 1200, $product['name'])
    ->setSiteName('Audio Store')
    ->setCustom('product:price:amount', $product['price'])
    ->setCustom('product:price:currency', $product['currency']);

echo $openGraph->render();