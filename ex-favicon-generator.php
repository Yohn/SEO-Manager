<?php
/**
 * Standalone Favicon Generator Script
 *
 * Usage: php generate_favicons.php path/to/your/512x512-logo.png
 *
 * This script generates all required favicon sizes from a single 512x512 source image
 * using your existing Favicons class with GD and WebP output.
 */

require_once __DIR__ . '/vendor/autoload.php'; // Adjust path as needed

use Yohns\SEO\Favicons;

class FaviconGenerator {
	private Favicons $favicons;
	private array $config;
	private string $sourceImage;

	public function __construct(string $sourceImage) {
		$this->sourceImage = $sourceImage;
		$this->loadConfig();
		$this->validateSourceImage();
		$this->initializeFavicons();
	}

	/**
	 * Load configuration from config file
	 */
	private function loadConfig(): void {
		$configFile = __DIR__ . '/lib/config/favicon.php';

		if (!file_exists($configFile)) {
			throw new \RuntimeException("Configuration file not found: {$configFile}");
		}

		$this->config = require $configFile;
	}

	/**
	 * Validate the source image meets requirements
	 */
	private function validateSourceImage(): void {
		if (!file_exists($this->sourceImage)) {
			throw new \InvalidArgumentException("Source image not found: {$this->sourceImage}");
		}

		$imageInfo = getimagesize($this->sourceImage);
		if ($imageInfo === false) {
			throw new \InvalidArgumentException("Invalid image file: {$this->sourceImage}");
		}

		$width = $imageInfo[0];
		$height = $imageInfo[1];
		$mimeType = $imageInfo['mime'];

		// Validate image is exactly 512x512
		if ($width !== 512 || $height !== 512) {
			throw new \InvalidArgumentException(
				"Source image must be exactly 512x512 pixels. " .
				"Current size: {$width}x{$height}. " .
				"Please resize your image to 512x512 before running this script."
			);
		}

		// Validate supported format
		$supportedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
		if (!in_array($mimeType, $supportedTypes)) {
			throw new \InvalidArgumentException(
				"Unsupported image format: {$mimeType}. " .
				"Supported formats: JPEG, PNG, GIF, WebP"
			);
		}

		echo "✓ Source image validated: {$width}x{$height}, {$mimeType}\n";
	}

	/**
	 * Initialize Favicons class with configuration
	 */
	private function initializeFavicons(): void {
		$this->favicons = new Favicons($this->config['favicons']);
	}

	/**
	 * Create output directory if it doesn't exist
	 */
	private function ensureOutputDirectory(): void {
		$outputDir = $this->config['favicons']['base_path'];

		if (!is_dir($outputDir)) {
			if (!mkdir($outputDir, 0755, true)) {
				throw new \RuntimeException("Failed to create output directory: {$outputDir}");
			}
			echo "✓ Created output directory: {$outputDir}\n";
		} else {
			echo "✓ Output directory exists: {$outputDir}\n";
		}
	}

	/**
	 * Generate all favicon files
	 */
	public function generate(): array {
		echo "Starting favicon generation...\n";
		echo "Source: {$this->sourceImage}\n";
		echo "Output: {$this->config['favicons']['base_path']}\n\n";

		$this->ensureOutputDirectory();

		// Generate all favicon sizes
		$generated = $this->favicons->generate($this->sourceImage, [
			'method' => 'gd',
			'output_dir' => $this->config['favicons']['base_path'],
			'types' => ['favicon', 'apple-touch', 'android', 'mstile'],
			'quality' => 90
		]);

		$this->displayResults($generated);
		$this->generateManifest();
		$this->showUsageExample();

		return $generated;
	}

	/**
	 * Display generation results
	 */
	private function displayResults(array $generated): void {
		echo "Generated files:\n";
		echo "================\n";

		if (empty($generated)) {
			echo "No files were generated (they may already exist)\n";
			return;
		}

		foreach ($generated as $filename => $filepath) {
			$filesize = $this->formatFileSize(filesize($filepath));
			echo "✓ {$filename} ({$filesize})\n";
		}

		echo "\nTotal files generated: " . count($generated) . "\n\n";
	}

	/**
	 * Generate web manifest file
	 */
	private function generateManifest(): void {
		$manifestData = $this->config['favicons']['manifest'] ?? [];
		$manifestJson = $this->favicons->generateManifest($manifestData);

		$manifestPath = $this->config['favicons']['base_path'] . '/manifest.json';

		if (file_put_contents($manifestPath, $manifestJson)) {
			echo "✓ Generated manifest.json\n";
		} else {
			echo "✗ Failed to generate manifest.json\n";
		}
	}

	/**
	 * Show usage example for HTML integration
	 */
	private function showUsageExample(): void {
		echo "\nHTML Integration Example:\n";
		echo "=========================\n";
		echo "Add this to your <head> section:\n\n";
		echo $this->favicons->render();
		echo "\n\nFavicon generation complete!\n";
	}

	/**
	 * Format file size for display
	 */
	private function formatFileSize(int $bytes): string {
		$units = ['B', 'KB', 'MB'];
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.1f %s", $bytes / (1024 ** $factor), $units[$factor]);
	}
}

// Script execution
if ($argc < 2) {
	echo "Usage: php generate_favicons.php <path-to-512x512-image>\n";
	echo "Example: php generate_favicons.php logo.png\n";
	exit(1);
}

try {
	$sourceImage = $argv[1];
	$generator = new FaviconGenerator($sourceImage);
	$generator->generate();

	echo "\n🎉 Success! All favicons have been generated.\n";

} catch (Exception $e) {
	echo "\n❌ Error: " . $e->getMessage() . "\n";
	exit(1);
}