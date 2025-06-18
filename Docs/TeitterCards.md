# TwitterCards Class

Manages Twitter Card meta tags for optimized Twitter/X sharing.

## Constants

```php
private const VALID_CARD_TYPES = [
    'summary', 'summary_large_image', 'app', 'player'
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
        'card' => 'summary_large_image',
        'site' => '@yoursite'
    ]
];
$twitterCards = new TwitterCards($config);
```

## Methods

### setCard()
Sets the Twitter Card type.

```php
public function setCard(string $type): self
```

#### Valid Types
- `summary` - Default card with small image
- `summary_large_image` - Card with large image
- `app` - App download card
- `player` - Video/audio player card

### setSite()
Sets the Twitter @username for the website.

```php
public function setSite(string $username): self
```

### setCreator()
Sets the Twitter @username for content creator.

```php
public function setCreator(string $username): self
```

### setTitle()
Sets title (max 70 characters).

```php
public function setTitle(string $title): self
```

### setDescription()
Sets description (max 200 characters).

```php
public function setDescription(string $description): self
```

### setImage()
Sets image with optional alt text.

```php
public function setImage(string $url, ?string $alt = null): self
```

### setPlayer()
Sets player card properties.

```php
public function setPlayer(string $url, int $width, int $height, ?string $stream = null): self
```

### setApp()
Sets app card properties.

```php
public function setApp(array $properties): self
```

## Complete Examples

### Article with Large Image
```php
$twitter = new TwitterCards();

$twitter
    ->setCard('summary_large_image')
    ->setSite('@techblog')
    ->setCreator('@johndoe')
    ->setTitle('10 Tips for Better Twitter Cards')
    ->setDescription('Learn how to optimize your Twitter Cards for maximum engagement and click-through rates.')
    ->setImage('https://blog.example.com/twitter-cards-guide.jpg', 'Twitter Cards Guide Cover');

echo $twitter->render();
```

Output:
```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@techblog">
<meta name="twitter:creator" content="@johndoe">
<meta name="twitter:title" content="10 Tips for Better Twitter Cards">
<meta name="twitter:description" content="Learn how to optimize your Twitter Cards for maximum engagement and click-through rates.">
<meta name="twitter:image" content="https://blog.example.com/twitter-cards-guide.jpg">
<meta name="twitter:image:alt" content="Twitter Cards Guide Cover">
```

### Video Player Card
```php
$twitter = new TwitterCards();

$twitter
    ->setCard('player')
    ->setSite('@videoplatform')
    ->setTitle('Introduction to Web Development')
    ->setDescription('A comprehensive video tutorial covering the basics of modern web development.')
    ->setImage('https://videos.example.com/thumbnails/web-dev-intro.jpg')
    ->setPlayer('https://videos.example.com/player/web-dev-intro', 640, 360,
                'https://videos.example.com/streams/web-dev-intro.m3u8');

echo $twitter->render();
```

### Mobile App Card
```php
$twitter = new TwitterCards();

$twitter
    ->setCard('app')
    ->setSite('@myappcompany')
    ->setTitle('My Amazing App')
    ->setDescription('The best app for managing your daily tasks.')
    ->setApp([
        'iphone' => [
            'id' => '123456789',
            'name' => 'My Amazing App',
            'url' => 'myapp://home'
        ],
        'ipad' => [
            'id' => '123456789',
            'name' => 'My Amazing App HD',
            'url' => 'myapp://home'
        ],
        'googleplay' => [
            'id' => 'com.example.myapp',
            'name' => 'My Amazing App',
            'url' => 'myapp://home'
        ]
    ]);

echo $twitter->render();
```

### E-commerce Product
```php
$twitter = new TwitterCards();

$product = [
    'name' => 'Premium Wireless Mouse',
    'description' => 'Ergonomic wireless mouse with 3-year battery life and precision tracking.',
    'price' => '$49.99',
    'image' => 'https://shop.example.com/products/wireless-mouse.jpg'
];

// Twitter automatically truncates if too long
$twitter
    ->setCard('summary_large_image')
    ->setSite('@techstore')
    ->setTitle($product['name'] . ' - ' . $product['price'])
    ->setDescription($product['description'])
    ->setImage($product['image'], $product['name']);

// Validate
$errors = $twitter->validate();
if (empty($errors)) {
    echo $twitter->render();
}
```

### Multi-language Support
```php
$twitter = new TwitterCards();

$lang = $_GET['lang'] ?? 'en';
$content = [
    'en' => [
        'title' => 'Welcome to Our Platform',
        'description' => 'Join thousands of users worldwide'
    ],
    'es' => [
        'title' => 'Bienvenido a Nuestra Plataforma',
        'description' => 'Únete a miles de usuarios en todo el mundo'
    ]
];

$twitter
    ->setCard('summary_large_image')
    ->setSite('@platform')
    ->setTitle($content[$lang]['title'])
    ->setDescription($content[$lang]['description'])
    ->setImage('https://platform.example.com/social/' . $lang . '.jpg');

echo $twitter->render();
```

## Validation

```php
$twitter = new TwitterCards();

// Missing required tags for player card
$twitter
    ->setCard('player')
    ->setTitle('Video Title');

$errors = $twitter->validate();
// Output: ["Required Twitter Card tag missing: twitter:player",
//          "Required Twitter Card tag missing: twitter:player:width",
//          "Required Twitter Card tag missing: twitter:player:height"]
```

## Character Limits

- **Title**: 70 characters (auto-truncated)
- **Description**: 200 characters (auto-truncated)
- **Image Alt**: 420 characters (auto-truncated)

## Image Requirements

- **Minimum size**: 144x144 pixels
- **Maximum size**: 4096x4096 pixels
- **File size**: Max 5MB
- **Formats**: JPG, PNG, WEBP, GIF
- **Aspect ratios**:
  - Summary card: 1:1
  - Summary large image: 2:1

## Best Practices

1. Always include `@site` for brand attribution
2. Use `@creator` for content authors
3. Choose appropriate card type for content
4. Optimize images for 2:1 or 1:1 ratios
5. Keep descriptions concise and engaging
6. Test with Twitter Card Validator

## Debugging

Use Twitter's Card Validator: https://cards-dev.twitter.com/validator

Common issues:
- Images must be publicly accessible
- HTTPS required for all URLs
- Allow Twitter's crawler in robots.txt