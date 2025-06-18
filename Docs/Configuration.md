# Configuration

> Missing Favicon config.

Set default values for SEO components using the Config class.

## Basic Setup

Create a configuration file at `lib/config/seo.php`:

```php
<?php
return [
    'meta' => [
        'defaults' => [
            'viewport' => 'width=device-width, initial-scale=1',
            'robots' => 'index, follow',
            'keywords' => 'default, keywords',
            'favicon' => '/favicon.ico'
        ]
    ],

    'opengraph' => [
        'defaults' => [
            'type' => 'website',
            'site_name' => 'Your Site Name',
            'locale' => 'en_US',
            'image' => 'https://yoursite.com/default-og.jpg'
        ]
    ],

    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@yoursite',
            'creator' => '@defaultcreator'
        ]
    ],

    'schema' => [
        'defaults' => [
            'organization' => [
                'name' => 'Your Organization',
                'url' => 'https://yoursite.com',
                'logo' => 'https://yoursite.com/logo.png',
                'email' => 'info@yoursite.com',
                'telephone' => '+1-555-123-4567'
            ]
        ]
    ]
];
```

## Usage with SEOManager

```php
use Yohns\Core\Config;
use Yohns\SEO\SEOManager;

$config = new Config(__DIR__ . '/lib/config');
$seo = new SEOManager($config);

// Defaults are automatically applied
echo $seo->render();
```

## Environment-Specific Configuration

```php
// lib/config/seo.php
$environment = $_ENV['APP_ENV'] ?? 'production';

$baseConfig = [
    'meta' => [
        'defaults' => [
            'viewport' => 'width=device-width, initial-scale=1'
        ]
    ]
];

$envConfigs = [
    'development' => [
        'meta' => [
            'defaults' => [
                'robots' => 'noindex, nofollow'
            ]
        ]
    ],
    'production' => [
        'meta' => [
            'defaults' => [
                'robots' => 'index, follow'
            ]
        ]
    ]
];

return array_merge_recursive($baseConfig, $envConfigs[$environment] ?? []);
```

## Without Configuration

SEOManager works without configuration:

```php
$seo = new SEOManager(); // No config
$seo->meta()->setTitle('Page Title');
echo $seo->render();
```