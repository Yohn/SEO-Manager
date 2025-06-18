<?php
// File: examples/favicon-usage.php

use Yohns\Core\Config;
use Yohns\SEO\SEOManager;
use Yohns\SEO\Favicons;

require_once __DIR__ . '/../vendor/autoload.php';

// Example 1: Basic usage with SEOManager
$config = new Config(__DIR__ . '/../lib/config');
$seo = new SEOManager($config);

// Favicons are automatically loaded from config
echo $seo->favicons()->render();

// Example 2: Standalone Favicons usage
$favicons = new Favicons([
	'base_path' => __DIR__ . '/../public/icons',
	'base_url' => '/icons',
	'app_name' => 'My Awesome App',
	'mobile_web_app_capable' => true,
	'theme-color' => '#4F46E5'
]);

// Set individual icons
$favicons
	->setFavicon('/favicon.ico')
	->setPngIcon('16x16', '/icons/favicon-16x16.png')
	->setPngIcon('32x32', '/icons/favicon-32x32.png')
	->setPngIcon('48x48', '/icons/favicon-48x48.png')
	->setAppleTouchIcon('152x152', '/icons/apple-touch-icon-152x152.png')
	->setAppleTouchIcon('167x167', '/icons/apple-touch-icon-167x167.png')
	->setAppleTouchIcon('180x180', '/icons/apple-touch-icon.png')
	->setMsTileImage('/icons/mstile-150x150.png')
	->setMsTileColor('#ffffff')
	->setSafariPinnedTab('/icons/safari-pinned-tab.svg', '#5bbad5')
	->setThemeColor('#4F46E5')
	->setManifest('/manifest.json');

echo $favicons->render();

// Example 3: Generate favicons from source image using GD
$favicons = new Favicons([
	'base_path' => __DIR__ . '/../public/icons',
	'base_url' => '/icons',
	'generation' => [
		'method' => 'gd'
	]
]);

try {
	// Ensure source image is large enough (minimum 512x512)
	$sourceImage = __DIR__ . '/../assets/logo-high-res.png';

	// Check source size first
	$imageInfo = getimagesize($sourceImage);
	if ($imageInfo) {
		echo "Source image: {$imageInfo[0]}x{$imageInfo[1]}\n";
		if (min($imageInfo[0], $imageInfo[1]) < 512) {
			throw new Exception("Source image too small. Please use at least 512x512.");
		}
	}

	$generated = $favicons->generate(
		$sourceImage,
		[
			'output_dir' => __DIR__ . '/../public/icons',
			'types' => ['favicon', 'apple-touch', 'android', 'mstile'],
			'quality' => 90  // WebP quality
		]
	);

	echo "Generated icons (WebP format):\n";
	foreach ($generated as $filename => $path) {
		$size = filesize($path);
		echo "- $filename: " . round($size / 1024, 2) . " KB\n";
	}
} catch (Exception $e) {
	echo "Error generating icons: " . $e->getMessage() . "\n";
	if (strpos($e->getMessage(), 'too small') !== false) {
		echo "Tip: Use an image at least 512x512, ideally 1024x1024 or larger.\n";
	}
}

// Example 4: Generate favicons using ImageMagick
$favicons = new Favicons([
	'base_path' => __DIR__ . '/../public/icons',
	'base_url' => '/icons',
	'generation' => [
		'method' => 'imagemagick',
		'imagick_path' => '/usr/bin/convert' // Adjust path as needed
	]
]);

try {
	// ImageMagick handles SVG well and produces excellent WebP quality
	$generated = $favicons->generate(
		__DIR__ . '/../assets/logo.svg', // Can use SVG with ImageMagick
		[
			'output_dir' => __DIR__ . '/../public/icons',
			'quality' => 95  // Higher quality for WebP
		]
	);

	echo "Generated " . count($generated) . " WebP icon files\n";

	// Show compression savings
	$totalSize = 0;
	foreach ($generated as $file => $path) {
		$totalSize += filesize($path);
	}
	echo "Total size: " . round($totalSize / 1024, 2) . " KB (WebP format)\n";
} catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
}

// Example 5: Generate and setup complete favicon set
$config = new Config(__DIR__ . '/../lib/config');
$seo = new SEOManager($config);

// Check if icons need to be generated
$iconsDir = __DIR__ . '/../public/icons';
if (!file_exists($iconsDir . '/favicon.ico')) {
	echo "Generating favicon files...\n";

	try {
		// Validate source image size
		$sourceImage = __DIR__ . '/../assets/logo.png';
		$imageInfo = getimagesize($sourceImage);

		if (!$imageInfo || min($imageInfo[0], $imageInfo[1]) < 512) {
			throw new Exception(
				"Source image must be at least 512x512. Current: {$imageInfo[0]}x{$imageInfo[1]}\n" .
				"Recommended: 1024x1024 or larger for best quality."
			);
		}

		$generated = $seo->favicons()->generate(
			$sourceImage,
			[
				'output_dir' => $iconsDir,
				'quality' => 90
			]
		);

		// Icons are automatically set after generation
		echo "Generated " . count($generated) . " WebP files\n";

		// Note about existing files
		echo "Note: Existing files were skipped to preserve customizations.\n";
	} catch (Exception $e) {
		echo "Generation failed: " . $e->getMessage() . "\n";
	}
}

// Render favicon tags
echo $seo->favicons()->render();

// Example 6: Generate web manifest
$favicons = new Favicons([
	'app_name' => 'Todo App',
	'app_short_name' => 'Todo',
	'base_url' => '/icons'
]);

// Generate manifest content
$manifestContent = $favicons->generateManifest([
	'name' => 'Todo List Application',
	'short_name' => 'Todo',
	'description' => 'A simple todo list app',
	'start_url' => '/',
	'display' => 'standalone',
	'theme_color' => '#2196F3',
	'background_color' => '#ffffff',
	'orientation' => 'portrait-primary'
]);

// Save manifest file
file_put_contents(__DIR__ . '/../public/manifest.json', $manifestContent);

// Example 7: Different configurations for environments
$environment = $_ENV['APP_ENV'] ?? 'production';

$faviconConfigs = [
	'development' => [
		'base_url' => '/dev-icons',
		'theme-color' => '#ff0000', // Red for dev
		'app_name' => 'MyApp (DEV)'
	],
	'staging' => [
		'base_url' => '/staging-icons',
		'theme-color' => '#ff9800', // Orange for staging
		'app_name' => 'MyApp (STAGING)'
	],
	'production' => [
		'base_url' => '/icons',
		'theme-color' => '#4CAF50', // Green for production
		'app_name' => 'MyApp'
	]
];

$favicons = new Favicons($faviconConfigs[$environment]);
echo $favicons->render();

// Example 8: Batch processing multiple source images
$sources = [
	'light' => __DIR__ . '/../assets/logo-light.png',
	'dark' => __DIR__ . '/../assets/logo-dark.png'
];

foreach ($sources as $theme => $sourceImage) {
	$outputDir = __DIR__ . "/../public/icons/$theme";

	if (!is_dir($outputDir)) {
		mkdir($outputDir, 0755, true);
	}

	$favicons = new Favicons([
		'base_path' => $outputDir,
		'base_url' => "/icons/$theme"
	]);

	try {
		$favicons->generate($sourceImage, [
			'output_dir' => $outputDir,
			'types' => ['favicon', 'apple-touch', 'android']
		]);
		echo "Generated $theme theme icons\n";
	} catch (Exception $e) {
		echo "Error generating $theme icons: " . $e->getMessage() . "\n";
	}
}

// Example 9: CLI script for favicon generation
// File: scripts/generate-favicons.php

if (php_sapi_name() !== 'cli') {
	die('This script must be run from command line');
}

$options = getopt('s:o:m:q::', ['source:', 'output:', 'method::', 'quality::']);

$sourceImage = $options['s'] ?? $options['source'] ?? null;
$outputDir = $options['o'] ?? $options['output'] ?? './public/icons';
$method = $options['m'] ?? $options['method'] ?? 'gd';
$quality = $options['q'] ?? $options['quality'] ?? 90;

if (!$sourceImage) {
	echo "Usage: php generate-favicons.php -s <source-image> [-o <output-dir>] [-m <method>] [-q <quality>]\n";
	echo "  -s, --source   Source image file (required, min 512x512)\n";
	echo "  -o, --output   Output directory (default: ./public/icons)\n";
	echo "  -m, --method   Generation method: gd or imagemagick (default: gd)\n";
	echo "  -q, --quality  WebP quality 1-100 (default: 90)\n";
	echo "\nExample: php generate-favicons.php -s logo.png -q 95\n";
	exit(1);
}

// Validate source image
if (!file_exists($sourceImage)) {
	echo "Error: Source image not found: $sourceImage\n";
	exit(1);
}

$imageInfo = getimagesize($sourceImage);
if (!$imageInfo) {
	echo "Error: Invalid image file\n";
	exit(1);
}

echo "Source image: {$imageInfo[0]}x{$imageInfo[1]} {$imageInfo['mime']}\n";

if (min($imageInfo[0], $imageInfo[1]) < 512) {
	echo "Error: Image too small. Minimum size is 512x512\n";
	echo "Your image would require upscaling, which reduces quality.\n";
	echo "Please provide a larger source image.\n";
	exit(1);
}

$config = [
	'generation' => ['method' => $method]
];

if ($method === 'imagemagick') {
	// Try to find ImageMagick
	$possiblePaths = [
		'/usr/bin/convert',
		'/usr/local/bin/convert',
		'/opt/homebrew/bin/convert',
		'C:\\Program Files\\ImageMagick-7.1.0-Q16\\magick.exe'
	];

	foreach ($possiblePaths as $path) {
		if (file_exists($path)) {
			$config['generation']['imagick_path'] = $path;
			break;
		}
	}
}

$favicons = new Favicons($config);

try {
	echo "Generating favicons from $sourceImage...\n";
	$generated = $favicons->generate($sourceImage, [
		'output_dir' => $outputDir,
		'types' => ['favicon', 'apple-touch', 'android', 'mstile']
	]);

	echo "Successfully generated " . count($generated) . " files:\n";
	foreach ($generated as $file => $path) {
		echo "  ✓ $file\n";
	}

	// Generate manifest
	$manifest = $favicons->generateManifest();
	file_put_contents($outputDir . '/manifest.json', $manifest);
	echo "  ✓ manifest.json\n";

} catch (Exception $e) {
	echo "Error: " . $e->getMessage() . "\n";
	exit(1);
}

// Example 10: Integration with build process
// File: build/generate-assets.php

class AssetBuilder {
	private Favicons $favicons;
	private string $sourceDir;
	private string $outputDir;

	public function __construct(string $sourceDir, string $outputDir) {
		$this->sourceDir = $sourceDir;
		$this->outputDir = $outputDir;
		$this->favicons = new Favicons([
			'base_path' => $outputDir . '/icons',
			'base_url' => '/assets/icons'
		]);
	}

	public function buildFavicons(): void {
		$sourceImage = $this->sourceDir . '/logo/logo-master.png';
		$outputPath = $this->outputDir . '/icons';

		if (!is_dir($outputPath)) {
			mkdir($outputPath, 0755, true);
		}

		// Check if rebuild needed
		$sourceTime = filemtime($sourceImage);
		$faviconPath = $outputPath . '/favicon.ico';

		if (file_exists($faviconPath) && filemtime($faviconPath) >= $sourceTime) {
			echo "Favicons are up to date\n";
			return;
		}

		echo "Building favicons...\n";

		try {
			$generated = $this->favicons->generate($sourceImage, [
				'output_dir' => $outputPath
			]);

			// Generate manifest
			$manifest = $this->favicons->generateManifest([
				'name' => 'My Application',
				'short_name' => 'MyApp',
				'start_url' => '/',
				'display' => 'standalone'
			]);

			file_put_contents($outputPath . '/manifest.json', $manifest);

			echo "Generated " . (count($generated) + 1) . " files\n";

		} catch (Exception $e) {
			throw new RuntimeException("Failed to generate favicons: " . $e->getMessage());
		}
	}
}

// Usage
$builder = new AssetBuilder(__DIR__ . '/../assets', __DIR__ . '/../public/assets');
$builder->buildFavicons();

// Example 11: Dynamic favicon switching (dark mode)
class DynamicFavicons {
	private array $themes = [];

	public function addTheme(string $name, Favicons $favicons): void {
		$this->themes[$name] = $favicons;
	}

	public function renderWithMediaQuery(): string {
		$output = [];

		// Default (light) theme
		if (isset($this->themes['light'])) {
			$lightTags = explode("\n", $this->themes['light']->render());
			foreach ($lightTags as $tag) {
				if (strpos($tag, 'rel="icon"') !== false && strpos($tag, 'type="image/png"') !== false) {
					// Add media query for light mode
					$tag = str_replace('>', ' media="(prefers-color-scheme: light)">', $tag);
				}
				$output[] = $tag;
			}
		}

		// Dark theme
		if (isset($this->themes['dark'])) {
			$darkTags = explode("\n", $this->themes['dark']->render());
			foreach ($darkTags as $tag) {
				if (strpos($tag, 'rel="icon"') !== false && strpos($tag, 'type="image/png"') !== false) {
					// Add media query for dark mode
					$tag = str_replace('>', ' media="(prefers-color-scheme: dark)">', $tag);
				}
				$output[] = $tag;
			}
		}

		return implode("\n", array_unique($output));
	}
}

// Usage
$dynamicFavicons = new DynamicFavicons();

$lightFavicons = new Favicons(['base_url' => '/icons/light']);
$lightFavicons->setPngIcon('32x32', '/icons/light/favicon-32x32.png');

$darkFavicons = new Favicons(['base_url' => '/icons/dark']);
$darkFavicons->setPngIcon('32x32', '/icons/dark/favicon-32x32.png');

$dynamicFavicons->addTheme('light', $lightFavicons);
$dynamicFavicons->addTheme('dark', $darkFavicons);

echo $dynamicFavicons->renderWithMediaQuery();