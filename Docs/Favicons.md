## Complete Examples

### Basic Setup
```php
$favicons = new Favicons([
    'base_url' => '/icons',
    'app_name' => 'My App',
    'theme-color' => '#4F46E5'
]);

// Manually set icons (WebP format)
$favicons
    ->setFavicon('/favicon.ico')
    ->setPngIcon('32x32', '/icons/favicon-32x32.webp')
    ->setAppleTouchIcon('180x180', '/icons/apple-touch-icon-180x180.webp')
    ->setManifest('/manifest.json');

echo $favicons->render();
```

### Auto-Generation with Size Validation
```php
$favicons = new Favicons([
    'base_path' => __DIR__ . '/public/icons',
    'base_url' => '/icons',
    'generation' => ['method' => 'gd']
]);

try {
    // Source must be at least 512x512
    $sourceImage = __DIR__ . '/assets/logo-1024x1024.png';

    // Generate all icons (WebP format)
    $generated = $favicons->generate($sourceImage, [
        'quality' => 90  // WebP quality
    ]);

    echo "Generated " . count($generated) . " WebP icons\n";

    // Generate manifest
    $manifest = $favicons->generateManifest([
        'name' => 'My Application'
    ]);
    file_put_contents(__DIR__ . '/public/manifest.json', $manifest);

} catch (InvalidArgumentException $e) {
    if (strpos($e->getMessage(), 'too small') !== false) {
        echo "Error: Please provide a larger image (minimum 512x512)\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
```

### Check and Generate Missing Icons
```php
$favicons = new Favicons([
    'base_path' => __DIR__ . '/public/icons',
    'base_url' => '/icons',
    'generation' => ['method' => 'gd']
]);

// The generate method automatically skips existing files
$generated = $favicons->generate(__DIR__ . '/assets/logo-2048x2048.png');

// Will show "Skipping existing file:" messages for files that already exist
// Only generates missing files
```

### Auto-Generation with ImageMagick
```php
$favicons = new Favicons([
    'base_path' => __DIR__ . '/public/icons',
    'base_url' => '/icons',
    'generation' => [
        'method' => 'imagemagick',
        'imagick_path' => '/usr/bin/convert'
    ]
]);

// ImageMagick handles SVG well and produces high-quality WebP
$generated = $favicons->generate(__DIR__ . '/assets/logo.svg', [
    'quality' => 95  // Higher quality for WebP
]);
```

### Progressive Web App Setup
```php
$favicons = new Favicons([
    'app_name' => 'Todo List PWA',
    'app_short_name' => 'Todo',
    'mobile_web_app_capable' => true,
    'theme-color' => '#2196F3'
]);

// Generate from high-res source (minimum 512x512, recommended 1024x1024+)
$favicons->generate('./logo-2048x2048.png', [
    'output_dir' => './public/icons',
    'quality' => 90
]);

// Create manifest with PWA settings
$manifest = $favicons->generateManifest([
    'display' => 'standalone',
    'orientation' => 'portrait',
    'start_url' => '/?source=pwa',
    'scope' => '/',
    'description' => 'A simple todo list application',
    'categories' => ['productivity', 'utilities']
]);

file_put_contents('./public/manifest.json', $manifest);
```

### Size Validation Example
```php
$sourceImage = './uploads/user-logo.png';

// Check image size before processing
list($width, $height) = getimagesize($sourceImage);
$minSize = min($width, $height);

if ($minSize < 512) {
    echo "Error: Image is {$width}x{$height}. Minimum required size is 512x512.\n";
    echo "Please upload a larger image to ensure quality favicons.\n";
    exit;
}

if ($minSize < 1024) {
    echo "Warning: Image is {$width}x{$height}. Recommended minimum is 1024x1024.\n";
    echo "Consider using a larger image for better quality.\n";
}

$favicons = new Favicons(['base_path' => './public/icons']);
$favicons->generate($sourceImage);
```

## Output Example

```html
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" type="image/webp" sizes="16x16" href="/icons/favicon-16x16.webp">
<link rel="icon" type="image/webp" sizes="32x32" href="/icons/favicon-32x32.webp">
<link rel="icon" type="image/webp" sizes="48x48" href="/icons/favicon-48x48.webp">
<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-touch-icon-152x152.webp">
<link rel="apple-touch-icon" sizes="167x167" href="/icons/apple-touch-icon-167x167.webp">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon-180x180.webp">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icons/mstile-150x150.webp">
<link rel="mask-icon" href="/icons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#4F46E5">
<link rel="manifest" href="/manifest.json">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="My App">
<meta name="apple-mobile-web-app-title" content="My App">
```

## WebP vs PNG Comparison

### WebP Advantages
- ✅ 25-35% smaller file size
- ✅ Better compression quality
- ✅ Supports transparency
- ✅ Modern browser support (95%+)
- ✅ Faster loading times

### Browser Support
- Chrome: Full support
- Firefox: Full support
- Safari: iOS 14+ / macOS Big Sur+
- Edge: Full support

### Fallback Strategy
For older browser support, you can generate both WebP and PNG:
```php
// Generate WebP (primary)
$favicons->generate($source, ['output_dir' => './icons/webp']);

// Generate PNG fallback
// Modify FILE_PATTERNS constant or use custom implementation
```

## Image Size Guidelines

### Minimum Requirements
- **Absolute minimum**: 512x512 pixels
- **Recommended**: 1024x1024 pixels
- **Ideal**: 2048x2048 pixels or larger

### Why Size Matters
1. **Quality**: Downscaling preserves quality, upscaling creates pixelation
2. **Future-proof**: Devices continue to increase in resolution
3. **Retina displays**: Need 2x resolution for crisp icons
4. **Large format uses**: Windows tiles, PWA splash screens

### Size Check Example
```php
try {
    $favicons->generate('./logo.png');
} catch (InvalidArgumentException $e) {
    if (strpos($e->getMessage(), 'Source image too small') !== false) {
        // Extract current size from error message
        preg_match('/Current size: (\d+)x(\d+)/', $e->getMessage(), $matches);
        echo "Your image is {$matches[1]}x{$matches[2]}\n";
        echo "Please provide an image at least 512x512 pixels\n";
        echo "Recommended: 1024x1024 or larger\n";
    }
}
```

## Best Practices

1. **Source Image**:
   - Use PNG with transparency or SVG
   - Minimum 512x512, ideally 1024x1024+
   - Square aspect ratio
   - Centered logo with padding

2. **Generation**:
   - Generate once during build/deploy
   - Check for existing files to avoid regeneration
   - Use high quality setting (90+) for WebP

3. **Performance**:
   - Set long cache headers for icon files
   - Use CDN for icon delivery
   - Preload critical icons

4. **Testing**:
   - Verify on real devices
   - Check all icon sizes generated
   - Test manifest on mobile browsers

## Troubleshooting

### GD WebP Support
```php
// Check if GD supports WebP
if (function_exists('imagewebp')) {
    echo "WebP is supported\n";
} else {
    echo "WebP not supported - update GD or use ImageMagick\n";
}
```

### Source Image Too Small
```bash
# Check image dimensions
identify -format "%wx%h" image.png

# Resize if needed (ImageMagick)
convert small.png -resize 1024x1024 large.png
```

### Memory Issues with Large Images
```php
ini_set('memory_limit', '256M');
```

### File Permissions
```bash
chmod 755 /path/to/icons
chmod 644 /path/to/icons/*.webp
```
    # Favicons Class

Comprehensive favicon management with automatic generation support using GD or ImageMagick.

## Features

- Generate all required favicon sizes from a single source image
- **Only scales down** - prevents upscaling and pixelation
- **WebP output format** for better compression and quality
- Automatically skips existing files
- Support for GD and ImageMagick
- Web manifest generation
- Mobile web app configuration
- Safari pinned tabs
- Microsoft tiles
- Theme color support

## Requirements

- **Source Image**: Minimum 512x512 pixels (larger recommended)
- **PHP Extensions**: GD with WebP support or ImageMagick
- **Output Format**: WebP (except ICO for favicon.ico)

## Constructor

```php
public function __construct(array $config = [])
```

### Configuration Options

```php
$config = [
    'base_path' => '/path/to/icons',     // File system path
    'base_url' => '/icons',              // URL path
    'app_name' => 'My App',              // Application name
    'app_short_name' => 'App',           // Short name
    'mobile_web_app_capable' => true,    // Enable fullscreen mode
    'theme-color' => '#4F46E5',          // Browser theme color
    'ms-tile-color' => '#ffffff',        // Windows tile color
    'generation' => [
        'method' => 'gd',                // 'gd' or 'imagemagick'
        'imagick_path' => '/usr/bin/convert' // Path to ImageMagick
    ]
];
```

## Icon Types and Sizes (Auto-Generated)

The following sizes are automatically generated if not found in the output directory:

### Standard Favicon Sizes
- **ICO**: 16x16, 32x32, 48x48 (combined in one file)
- **WebP**: 16x16, 32x32, 48x48

### Apple Touch Icons
- 152x152 - iPad
- 167x167 - iPad Pro
- 180x180 - iPhone (default)

### Android/Chrome Icons
- 36x36, 48x48, 72x72, 96x96, 144x144, 192x192, 512x512

### Microsoft Tile
- 150x150

**Note**: All output files are in WebP format for optimal quality and file size.

## Methods

### setFavicon()
Set the base favicon.ico file.

```php
$favicons->setFavicon('/favicon.ico');
```

### setPngIcon()
Set PNG icon with specific size.

```php
$favicons->setPngIcon('32x32', '/icons/favicon-32x32.png');
```

### setAppleTouchIcon()
Set Apple touch icon for iOS devices.

```php
$favicons->setAppleTouchIcon('180x180', '/icons/apple-touch-icon.png');
```

### setMsTileImage() / setMsTileColor()
Configure Microsoft tile appearance.

```php
$favicons->setMsTileImage('/icons/mstile-150x150.png')
         ->setMsTileColor('#2196F3');
```

### setSafariPinnedTab()
Set Safari pinned tab icon (SVG).

```php
$favicons->setSafariPinnedTab('/icons/safari-pinned-tab.svg', '#5bbad5');
```

### setThemeColor()
Set browser theme color.

```php
$favicons->setThemeColor('#4F46E5');
```

### setManifest()
Set web manifest file path.

```php
$favicons->setManifest('/manifest.json');
```

### generate()
Generate all favicon files from source image.

```php
public function generate(string $sourceImage, array $options = []): array
```

#### Requirements
- **Minimum source size**: 512x512 pixels (prevents upscaling)
- **Recommended**: 1024x1024 or larger for best quality
- **Output format**: WebP (better compression than PNG)

#### Options
- `method` - 'gd' or 'imagemagick' (overrides config)
- `output_dir` - Where to save generated files
- `types` - Array of types to generate: ['favicon', 'apple-touch', 'android', 'mstile']
- `quality` - WebP quality (1-100, default: 90)

#### Example
```php
try {
    // Requires minimum 512x512 source image
    $generated = $favicons->generate('/path/to/logo-highres.png', [
        'output_dir' => './public/icons',
        'types' => ['favicon', 'apple-touch', 'android'],
        'quality' => 85
    ]);

    echo "Generated files:\n";
    foreach ($generated as $file => $path) {
        echo "- $file\n";
    }
} catch (InvalidArgumentException $e) {
    // Source image too small or not found
    echo "Error: " . $e->getMessage();
}
```

### generateManifest()
Generate web app manifest content.

```php
$manifest = $favicons->generateManifest([
    'name' => 'My Progressive Web App',
    'short_name' => 'MyPWA',
    'start_url' => '/',
    'display' => 'standalone',
    'orientation' => 'portrait',
    'theme_color' => '#2196F3',
    'background_color' => '#ffffff'
]);

file_put_contents('manifest.json', $manifest);
```

### render()
Output all favicon HTML tags.

```php
echo $favicons->render();
```

## Complete Examples

### Basic Setup
```php
$favicons = new Favicons([
    'base_url' => '/icons',
    'app_name' => 'My App',
    'theme-color' => '#4F46E5'
]);

// Manually set icons
$favicons
    ->setFavicon('/favicon.ico')
    ->setPngIcon('32x32', '/icons/favicon-32x32.png')
    ->setAppleTouchIcon('180x180', '/icons/apple-touch-icon.png')
    ->setManifest('/manifest.json');

echo $favicons->render();
```

### Auto-Generation with GD
```php
$favicons = new Favicons([
    'base_path' => __DIR__ . '/public/icons',
    'base_url' => '/icons',
    'generation' => ['method' => 'gd']
]);

// Generate all icons from source
$generated = $favicons->generate(__DIR__ . '/assets/logo.png');

// Generate manifest
$manifest = $favicons->generateManifest([
    'name' => 'My Application'
]);
file_put_contents(__DIR__ . '/public/manifest.json', $manifest);

// Render tags
echo $favicons->render();
```

### Auto-Generation with ImageMagick
```php
$favicons = new Favicons([
    'base_path' => __DIR__ . '/public/icons',
    'base_url' => '/icons',
    'generation' => [
        'method' => 'imagemagick',
        'imagick_path' => '/usr/bin/convert'
    ]
]);

// ImageMagick handles SVG well
$generated = $favicons->generate(__DIR__ . '/assets/logo.svg');
```

### Progressive Web App Setup
```php
$favicons = new Favicons([
    'app_name' => 'Todo List PWA',
    'app_short_name' => 'Todo',
    'mobile_web_app_capable' => true,
    'theme-color' => '#2196F3'
]);

// Generate from high-res source
$favicons->generate('./logo-highres.png', [
    'output_dir' => './public/icons'
]);

// Create manifest with PWA settings
$manifest = $favicons->generateManifest([
    'display' => 'standalone',
    'orientation' => 'portrait',
    'start_url' => '/?source=pwa',
    'scope' => '/',
    'description' => 'A simple todo list application',
    'categories' => ['productivity', 'utilities']
]);

file_put_contents('./public/manifest.json', $manifest);
```

## Output Example

```html
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="48x48" href="/icons/favicon-48x48.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="167x167" href="/icons/apple-touch-icon-167x167.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icons/mstile-150x150.png">
<link rel="mask-icon" href="/icons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#4F46E5">
<link rel="manifest" href="/manifest.json">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="My App">
<meta name="apple-mobile-web-app-title" content="My App">
```

## ImageMagick vs GD

### GD (Default)
- ✅ Usually pre-installed with PHP
- ✅ No external dependencies
- ✅ Good for PNG/JPEG
- ❌ Limited format support
- ❌ Basic image processing

### ImageMagick
- ✅ Superior image quality
- ✅ SVG support
- ✅ Better resizing algorithms
- ✅ More format support
- ❌ Requires installation
- ❌ Path configuration needed

## Common ImageMagick Paths

```php
// Linux
'/usr/bin/convert'
'/usr/local/bin/convert'

// macOS (Homebrew)
'/opt/homebrew/bin/convert'
'/usr/local/bin/convert'

// Windows
'C:\\Program Files\\ImageMagick-7.1.0-Q16\\magick.exe'
'C:\\Program Files\\ImageMagick\\convert.exe'
```

## Best Practices

1. **Source Image**: Use high-resolution source (at least 512x512)
2. **Format**: PNG with transparency or SVG for best results
3. **Generation**: Generate once during build/deploy
4. **Caching**: Set long cache headers for icon files
5. **Fallback**: Always include basic favicon.ico
6. **Testing**: Verify on real devices

## Troubleshooting

### GD Memory Issues
```php
ini_set('memory_limit', '256M');
```

### ImageMagick Not Found
```bash
# Find ImageMagick
which convert
whereis convert
```

### Permission Errors
```bash
chmod 755 /path/to/icons
```