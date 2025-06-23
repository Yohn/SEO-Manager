<?php

namespace Yohns\SEO;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class Favicons
 *
 * Manages favicon and icon generation for various platforms including Apple, Android, Microsoft, and browsers.
 * Supports dynamic generation from a source image using GD or ImageMagick.
 *
 * Example (ImageMagick):
 * ```php
 * $favicons = new \Yohns\SEO\Favicons([
 * 	'base_path' => __DIR__ . '/public',
 * 	'base_url' => '/public',
 * 	'app_name' => 'Example App',
 * 	'generation' => [
 * 		//'method' => 'gd' // Uncomment and remove the following 2 lines for GD usage
 * 		'method' => 'imagemagick',
 * 		'imagick_path' => 'magick'
 * 	]
 * ]);
 * $favicons = new \Yohns\SEO\Favicons([
 * 	'base_path' => __DIR__ . '/public',
 * 	'base_url' => '/public',
 * 	'app_name' => 'Example App',
 * 	'generation' => ['method' => 'gd']
 * ]);
 *
 * $favicons->generate(__DIR__ . '/logo.png');
 * echo $favicons->render();
 * ```
 * Example (GD):
 * ```php
 *
 * $favicons->generate(__DIR__ . '/logo.png');
 * echo $favicons->render();
 * ```
 * @package Yohns\SEO
 */

class Favicons {
	private array  $icons    = [];
	private array  $config   = [];
	private string $basePath = '';
	private string $baseUrl  = '';

	// Standard favicon sizes (automatically generated if not found)
	private const FAVICON_SIZES = [
		'favicon'     => ['16x16', '32x32', '48x48'],
		'apple-touch' => ['152x152', '167x167', '180x180'],
		'android'     => ['36x36', '48x48', '72x72', '96x96', '144x144', '192x192', '512x512'],
		'mstile'      => ['150x150']
	];

	// File naming patterns (WebP format)
	private const FILE_PATTERNS = [
		'favicon'     => 'favicon-%s.webp',
		'apple-touch' => 'apple-touch-icon-%s.webp',
		'android'     => 'android-chrome-%s.webp',
		'mstile'      => 'mstile-%s.webp'
	];

	// Minimum source image size
	private const MIN_SOURCE_SIZE = 512;

	/**
	 * Constructor to initialize Favicons with optional configuration.
	 *
	 * @param array $config Optional configuration for paths and icon settings.
	 */
	public function __construct(array $config = []) {
		$this->config = $config;
		$this->basePath = $config['base_path'] ?? '';
		$this->baseUrl = $config['base_url'] ?? '';
		$this->loadDefaults();
	}

	/**
	 * Load default icons from config
	 */
	private function loadDefaults(): void {
		if (isset($this->config['icons'])) {
			foreach ($this->config['icons'] as $type => $icon) {
				$this->icons[$type] = $icon;
			}
		}
	}

	/**
	 * Set the base favicon (.ico file).
	 *
	 * @param string $path Path to the favicon.ico file.
	 * @return self
	 */
	public function setFavicon(string $path): self {
		$this->icons['favicon'] = $path;
		return $this;
	}

	/**
	 * Set a PNG or WebP icon with a specified size.
	 *
	 * @param string $size Icon size in WIDTHxHEIGHT format (e.g., '32x32').
	 * @param string $path Path to the icon file.
	 * @return self
	 */
	public function setPngIcon(string $size, string $path): self {
		$this->icons['png'][$size] = $path;
		return $this;
	}

	/**
	 * Set an Apple touch icon.
	 *
	 * @param string $size Icon size in WIDTHxHEIGHT format (e.g., '180x180').
	 * @param string $path Path to the Apple touch icon file.
	 * @return self
	 */
	public function setAppleTouchIcon(string $size, string $path): self {
		$this->icons['apple-touch'][$size] = $path;
		return $this;
	}

	/**
	 * Set Microsoft tile image path.
	 *
	 * @param string $path Path to the MSTile image file.
	 * @return self
	 */
	public function setMsTileImage(string $path): self {
		$this->icons['mstile'] = $path;
		return $this;
	}

	/**
	 * Set Microsoft tile color.
	 *
	 * @param string $color Hex or CSS color value (e.g., '#ffffff').
	 * @return self
	 */
	public function setMsTileColor(string $color): self {
		$this->icons['ms-tile-color'] = $color;
		return $this;
	}

	/**
	 * Set Safari pinned tab icon path and color.
	 *
	 * @param string $path Path to the mask-icon SVG file.
	 * @param string $color Hex or CSS color value.
	 * @return self
	 */
	public function setSafariPinnedTab(string $path, string $color): self {
		$this->icons['safari-pinned-tab'] = [
			'path'  => $path,
			'color' => $color
		];
		return $this;
	}

	/**
	 * Set theme color for browser UI.
	 *
	 * @param string $color Hex or CSS color value.
	 * @return self
	 */
	public function setThemeColor(string $color): self {
		$this->icons['theme-color'] = $color;
		return $this;
	}

	/**
	 * Set the path to the web manifest file.
	 *
	 * @param string $path Path to the manifest.json file.
	 * @return self
	 */
	public function setManifest(string $path): self {
		$this->icons['manifest'] = $path;
		return $this;
	}

	/**
	 * Generate favicon files from source image
	 * Note: Automatically generates the following sizes if not found:
	 * - Favicon: 16x16, 32x32, 48x48
	 * - Apple Touch: 152x152, 167x167, 180x180
	 * - Android: 36x36, 48x48, 72x72, 96x96, 144x144, 192x192, 512x512
	 * - MS Tile: 150x150
	 *
	 * @param string $sourceImage Path to the source image file.
	 * @param array $options Optional generation settings: method, output_dir, types, quality.
	 * @return array Array of generated file paths keyed by filename.
	 */
	public function generate(string $sourceImage, array $options = []): array {
		if (!file_exists($sourceImage)) {
			throw new InvalidArgumentException("Source image not found: $sourceImage");
		}

		// Validate source image size
		$imageInfo = getimagesize($sourceImage);
		if ($imageInfo === false) {
			throw new InvalidArgumentException("Invalid image file: $sourceImage");
		}

		$sourceWidth = $imageInfo[0];
		$sourceHeight = $imageInfo[1];
		$minDimension = min($sourceWidth, $sourceHeight);

		if ($minDimension < self::MIN_SOURCE_SIZE) {
			throw new InvalidArgumentException(
				"Source image too small. Minimum size is " . self::MIN_SOURCE_SIZE . "x" . self::MIN_SOURCE_SIZE .
				" pixels. Current size: {$sourceWidth}x{$sourceHeight}. " .
				"Please provide a larger image to prevent upscaling and pixelation."
			);
		}

		$method = $options['method'] ?? $this->config['generation']['method'] ?? 'gd';
		$outputDir = $options['output_dir'] ?? $this->basePath;
		$generateTypes = $options['types'] ?? ['favicon', 'apple-touch', 'android', 'mstile'];
		$quality = $options['quality'] ?? 90; // WebP quality

		$generated = [];

		// Check which files already exist
		$existingFiles = $this->checkExistingFiles($outputDir, $generateTypes);

		switch ($method) {
			case 'gd':
				$generated = $this->generateWithGD(
					sourceImage: $sourceImage, outputDir: $outputDir,
					types: $generateTypes, quality: $quality, existingFiles: $existingFiles);
				break;
			case 'imagick':
			case 'imagemagick':
				$generated = $this->generateWithImageMagick($sourceImage, $outputDir, $generateTypes, $quality, $existingFiles);
				break;
			default:
				throw new InvalidArgumentException("Invalid generation method: $method");
		}

		// Generate ICO file if requested
		if (in_array('favicon', $generateTypes) && !file_exists($outputDir . '/favicon.ico')) {
			$this->generateIco($sourceImage, $outputDir . '/favicon.ico', $method);
			$generated['favicon.ico'] = $outputDir . '/favicon.ico';
		}

		return $generated;
	}

	/**
	 * Check which favicon files already exist in the output directory.
	 *
	 * @param string $outputDir Directory to check for existing files.
	 * @param array $types Icon types to check (e.g., 'favicon', 'apple-touch').
	 * @return array Array of existing filenames as keys.
	 */
	private function checkExistingFiles(string $outputDir, array $types): array {
		$existing = [];

		foreach ($types as $type) {
			if (!isset(self::FAVICON_SIZES[$type])) {
				continue;
			}

			foreach (self::FAVICON_SIZES[$type] as $size) {
				$filename = sprintf(self::FILE_PATTERNS[$type], $size);
				$filepath = $outputDir . '/' . $filename;

				if (file_exists($filepath)) {
					$existing[$filename] = true;
				}
			}
		}

		return $existing;
	}

	/**
	 * Generate favicon images using the GD extension.
	 *
	 * @param string $sourceImage Path to the source image.
	 * @param string $outputDir Output directory for generated icons.
	 * @param array $types Icon types to generate.
	 * @param int $quality WebP quality (0–100).
	 * @param array $existingFiles Array of existing filenames to skip.
	 * @return array Array of generated file paths keyed by filename.
	 */
	private function generateWithGD(
		string $sourceImage,
		string $outputDir,
		array $types,
		int $quality,
		array $existingFiles): array {
		if (!extension_loaded('gd')) {
			throw new RuntimeException("GD extension is not loaded");
		}

		// Check WebP support
		if (!function_exists('imagewebp')) {
			throw new RuntimeException("GD does not support WebP format. Please update GD or use ImageMagick.");
		}

		$generated = [];
		$info = getimagesize($sourceImage);

		// Load source image
		$source = match ($info['mime']) {
			'image/jpeg' => imagecreatefromjpeg($sourceImage),
			'image/png'  => imagecreatefrompng($sourceImage),
			'image/gif'  => imagecreatefromgif($sourceImage),
			'image/webp' => imagecreatefromwebp($sourceImage),
			default      => throw new InvalidArgumentException("Unsupported image type: {$info['mime']}")
		};

		$sourceWidth = imagesx($source);
		$sourceHeight = imagesy($source);

		// Ensure source is square or make it square
		if ($sourceWidth !== $sourceHeight) {
			$size = min($sourceWidth, $sourceHeight);
			$squareSource = imagecreatetruecolor($size, $size);

			// Preserve transparency
			imagealphablending($squareSource, false);
			imagesavealpha($squareSource, true);
			$transparent = imagecolorallocatealpha($squareSource, 255, 255, 255, 127);
			imagefilledrectangle($squareSource, 0, 0, $size, $size, $transparent);

			// Center crop
			$offsetX = ($sourceWidth - $size) / 2;
			$offsetY = ($sourceHeight - $size) / 2;

			imagecopyresampled(
				$squareSource, $source,
				0, 0, $offsetX, $offsetY,
				$size, $size, $size, $size
			);

			imagedestroy($source);
			$source = $squareSource;
			$sourceWidth = $sourceHeight = $size;
		}

		foreach ($types as $type) {
			if (!isset(self::FAVICON_SIZES[$type])) {
				continue;
			}

			foreach (self::FAVICON_SIZES[$type] as $size) {
				$filename = sprintf(self::FILE_PATTERNS[$type], $size);

				// Skip if file already exists
				if (isset($existingFiles[$filename])) {
					echo "Skipping existing file: $filename\n";
					continue;
				}

				[$width, $height] = explode('x', $size);
				$width = (int) $width;
				$height = (int) $height;

				// Only scale down, never up
				if ($width > $sourceWidth || $height > $sourceHeight) {
					echo "Warning: Skipping $filename - would require upscaling from {$sourceWidth}x{$sourceHeight}\n";
					continue;
				}

				// Create resized image
				$resized = imagecreatetruecolor($width, $height);

				// Preserve transparency
				imagealphablending($resized, false);
				imagesavealpha($resized, true);
				$transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
				imagefilledrectangle($resized, 0, 0, $width, $height, $transparent);

				// Copy and resize with high quality
				imagecopyresampled(
					$resized, $source,
					0, 0, 0, 0,
					$width, $height,
					$sourceWidth, $sourceHeight
				);

				// Generate filename
				$filepath = $outputDir . '/' . $filename;

				// Save as WebP
				imagewebp($resized, $filepath, $quality);
				imagedestroy($resized);

				$generated[$filename] = $filepath;
				$this->setPngIcon($size, $this->baseUrl . '/' . $filename);
			}
		}

		imagedestroy($source);
		return $generated;
	}

	/**
	 * Generate favicon images using ImageMagick.
	 *
	 * @param string $sourceImage Path to the source image.
	 * @param string $outputDir Output directory for generated icons.
	 * @param array $types Icon types to generate.
	 * @param int $quality WebP quality (0–100).
	 * @param array $existingFiles Array of existing filenames to skip.
	 * @return array Array of generated file paths keyed by filename.
	 */
	private function generateWithImageMagick(string $sourceImage, string $outputDir, array $types, int $quality, array $existingFiles): array {
		$imagickPath = $this->config['generation']['imagick_path'] ?? 'convert';

		// Test if ImageMagick is available
		$testCmd = escapeshellcmd($imagickPath) . ' -version';
		exec($testCmd, $output, $returnCode);
		if ($returnCode !== 0) {
			throw new RuntimeException("ImageMagick not found at: $imagickPath");
		}

		// Get source image dimensions
		$identifyCmd = sprintf('%s identify -format "%%wx%%h" "%s"',
			dirname($imagickPath) . '/identify',
			escapeshellarg($sourceImage)
		);
		exec($identifyCmd, $dimensions, $returnCode);

		if ($returnCode !== 0 || empty($dimensions[0])) {
			// Fallback to using convert
			$identifyCmd = sprintf('%s "%s" -format "%%wx%%h" info:',
				escapeshellcmd($imagickPath),
				escapeshellarg($sourceImage)
			);
			exec($identifyCmd, $dimensions);
		}

		if (!empty($dimensions[0]) && preg_match('/(\d+)x(\d+)/', $dimensions[0], $matches)) {
			$sourceWidth = (int) $matches[1];
			$sourceHeight = (int) $matches[2];
			$minDimension = min($sourceWidth, $sourceHeight);
		} else {
			throw new RuntimeException("Could not determine source image dimensions");
		}

		$generated = [];

		foreach ($types as $type) {
			if (!isset(self::FAVICON_SIZES[$type])) {
				continue;
			}

			foreach (self::FAVICON_SIZES[$type] as $size) {
				$filename = sprintf(self::FILE_PATTERNS[$type], $size);

				// Skip if file already exists
				if (isset($existingFiles[$filename])) {
					echo "Skipping existing file: $filename\n";
					continue;
				}

				[$width, $height] = explode('x', $size);
				$width = (int) $width;
				$height = (int) $height;

				// Only scale down, never up
				if ($width > $minDimension || $height > $minDimension) {
					echo "Warning: Skipping $filename - would require upscaling from {$sourceWidth}x{$sourceHeight}\n";
					continue;
				}

				$filepath = $outputDir . '/' . $filename;

				// ImageMagick command with high quality settings for WebP
				$cmd = sprintf(
					'%s "%s" -resize %s -background transparent -gravity center -extent %s -quality %d -define webp:lossless=false -define webp:method=6 "%s"',
					escapeshellcmd($imagickPath),
					escapeshellarg($sourceImage),
					$size,
					$size,
					$quality,
					escapeshellarg($filepath)
				);

				exec($cmd, $output, $returnCode);

				if ($returnCode === 0) {
					$generated[$filename] = $filepath;
					$this->setPngIcon($size, $this->baseUrl . '/' . $filename);
				} else {
					echo "Error generating $filename\n";
				}
			}
		}

		return $generated;
	}

	/**
	 * Generate a .ico file from a source image using GD or ImageMagick.
	 *
	 * @param string $sourceImage Path to the source image.
	 * @param string $outputPath Destination path for the generated .ico file.
	 * @param string $method Method to use for generation: 'gd', 'imagick', or 'imagemagick'.
	 */
	private function generateIco(string $sourceImage, string $outputPath, string $method): void {
		if ($method === 'imagemagick' || $method === 'imagick') {
			$imagickPath = $this->config['generation']['imagick_path'] ?? 'convert';
			$cmd = sprintf(
				'%s "%s" -resize 16x16 -resize 32x32 -resize 48x48 "%s"',
				escapeshellcmd($imagickPath),
				escapeshellarg($sourceImage),
				escapeshellarg($outputPath)
			);
			exec($cmd);
		} else {
			// For GD, create ICO with multiple sizes
			// This is a simplified version - full ICO generation is complex
			// Generate individual PNG files first
			$tempDir = dirname($outputPath) . '/temp_ico';
			if (!is_dir($tempDir)) {
				mkdir($tempDir, 0755, true);
			}

			// Generate the required sizes for ICO
			$this->generateWithGD($sourceImage, $tempDir, ['favicon'], 90, []);

			// For now, just copy the 48x48 as favicon.ico
			// (Full ICO generation would combine multiple sizes)
			$favicon48 = $tempDir . '/favicon-48x48.webp';
			if (file_exists($favicon48)) {
				// Convert WebP to ICO (simplified - just copy for now)
				copy($favicon48, $outputPath);
			}

			// Clean up temp files
			array_map('unlink', glob($tempDir . '/*'));
			rmdir($tempDir);
		}
	}

	/**
	 * Generate a web manifest JSON string.
	 *
	 * @param array $data Optional overrides for manifest properties.
	 * @return string JSON-formatted manifest string.
	 */
	public function generateManifest(array $data = []): string {
		$manifest = array_merge([
			'name'             => $data['name'] ?? $this->config['app_name'] ?? 'My App',
			'short_name'       => $data['short_name'] ?? $this->config['app_short_name'] ?? 'App',
			'theme_color'      => $this->icons['theme-color'] ?? '#ffffff',
			'background_color' => $data['background_color'] ?? '#ffffff',
			'display'          => $data['display'] ?? 'standalone',
			'orientation'      => $data['orientation'] ?? 'portrait',
			'start_url'        => $data['start_url'] ?? '/',
			'icons'            => []
		], $data);

		// Add icons
		foreach (self::FAVICON_SIZES['android'] as $size) {
			$filename = sprintf(self::FILE_PATTERNS['android'], $size);
			if (isset($this->icons['png'][$size])) {
				$manifest['icons'][] = [
					'src'   => $this->icons['png'][$size],
					'sizes' => $size,
					'type'  => 'image/png'
				];
			}
		}

		return json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Render the HTML `<link>` and `<meta>` tags for favicons.
	 *
	 * @return string HTML string containing all favicon-related tags.
	 */
	public function render(): string {
		$output = [];

		// Basic favicon
		if (isset($this->icons['favicon'])) {
			$output[] = sprintf('<link rel="icon" href="%s" type="image/x-icon">', htmlspecialchars($this->icons['favicon']));
			$output[] = sprintf('<link rel="shortcut icon" href="%s" type="image/x-icon">', htmlspecialchars($this->icons['favicon']));
		}

		// PNG/WebP icons
		if (isset($this->icons['png'])) {
			foreach ($this->icons['png'] as $size => $path) {
				// Determine type based on file extension
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$type = match (strtolower($ext)) {
					'webp'  => 'image/webp',
					'png'   => 'image/png',
					default => 'image/png'
				};

				$output[] = sprintf(
					'<link rel="icon" type="%s" sizes="%s" href="%s">',
					$type,
					htmlspecialchars($size),
					htmlspecialchars($path)
				);
			}
		}

		// Apple touch icons
		if (isset($this->icons['apple-touch'])) {
			foreach ($this->icons['apple-touch'] as $size => $path) {
				$output[] = sprintf(
					'<link rel="apple-touch-icon" sizes="%s" href="%s">',
					htmlspecialchars($size),
					htmlspecialchars($path)
				);
			}
		}

		// Microsoft tile
		if (isset($this->icons['ms-tile-color'])) {
			$output[] = sprintf('<meta name="msapplication-TileColor" content="%s">', htmlspecialchars($this->icons['ms-tile-color']));
		}
		if (isset($this->icons['mstile'])) {
			$output[] = sprintf('<meta name="msapplication-TileImage" content="%s">', htmlspecialchars($this->icons['mstile']));
		}

		// Safari pinned tab
		if (isset($this->icons['safari-pinned-tab'])) {
			$output[] = sprintf(
				'<link rel="mask-icon" href="%s" color="%s">',
				htmlspecialchars($this->icons['safari-pinned-tab']['path']),
				htmlspecialchars($this->icons['safari-pinned-tab']['color'])
			);
		}

		// Theme color
		if (isset($this->icons['theme-color'])) {
			$output[] = sprintf('<meta name="theme-color" content="%s">', htmlspecialchars($this->icons['theme-color']));
		}

		// Web manifest
		if (isset($this->icons['manifest'])) {
			$output[] = sprintf('<link rel="manifest" href="%s">', htmlspecialchars($this->icons['manifest']));
		}

		// Mobile web app capable
		if ($this->config['mobile_web_app_capable'] ?? false) {
			$output[] = '<meta name="mobile-web-app-capable" content="yes">';
			$output[] = '<meta name="apple-mobile-web-app-capable" content="yes">';
		}

		// App name
		if (isset($this->config['app_name'])) {
			$output[] = sprintf('<meta name="application-name" content="%s">', htmlspecialchars($this->config['app_name']));
			$output[] = sprintf('<meta name="apple-mobile-web-app-title" content="%s">', htmlspecialchars($this->config['app_name']));
		}

		return implode("\n", $output);
	}

	/**
	 * Export the internal icon configuration as an array.
	 *
	 * @return array Array of all configured icon paths and values.
	 */
	public function toArray(): array {
		return $this->icons;
	}
}