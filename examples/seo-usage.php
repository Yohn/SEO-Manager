<?php
// File: examples/seo-usage.php

use Yohns\Core\Config;
use Yohns\SEO\SEOManager;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize Config
$config = new Config(__DIR__ . '/../lib/config');

// Initialize SEO Manager
$seo = new SEOManager($config);

// Example 1: Basic page setup with favicons
$seo->setCommon([
	'title' => 'Welcome to Our Website',
	'description' => 'Discover amazing products and services that will transform your business.',
	'image' => 'https://example.com/images/hero.jpg',
	'url' => 'https://example.com'
]);

// Set up favicons (assuming they're already generated)
$seo->favicons()
	->setThemeColor('#4F46E5')
	->setManifest('/manifest.json');

// Example 2: Product page with schema and favicons
$seo->meta()
	->setTitle('Amazing Product - Buy Now')
	->setDescription('This amazing product will change your life. High quality, affordable price.')
	->setKeywords(['amazing product', 'buy online', 'best quality']);

$seo->openGraph()
	->setType('product')
	->setImage('https://example.com/products/amazing-product.jpg', 1200, 630, 'Amazing Product Image');

$seo->twitter()
	->setCard('summary_large_image')
	->setCreator('@productcreator');

// Ensure favicons are set for mobile shopping
$seo->favicons()
	->setThemeColor('#2196F3')
	->setMsTileColor('#2196F3');

// Add product schema
$productSchema = $seo->schema()->product([
	'name' => 'Amazing Product',
	'image' => 'https://example.com/products/amazing-product.jpg',
	'description' => 'This amazing product will change your life.',
	'brand' => 'Awesome Brand',
	'model' => 'AP-2024',
	'price' => '99.99',
	'currency' => 'USD',
	'review' => [
		'reviewBody' => 'Best product ever!',
		'ratingValue' => '5',
		'author' => 'John Doe'
	]
]);
$seo->schema()->add($productSchema);

// Example 3: Article page with complete favicon setup
$seo->meta()
	->setTitle('10 Tips for Better SEO in 2024')
	->setDescription('Learn the latest SEO strategies and techniques to improve your search rankings.')
	->setCanonical('https://example.com/blog/seo-tips-2024');

$seo->openGraph()
	->setType('article')
	->setArticle([
		'author' => 'Jane Smith',
		'published_time' => '2024-01-15T10:00:00Z',
		'modified_time' => '2024-01-20T14:30:00Z',
		'section' => 'SEO',
		'tag' => ['seo', 'marketing', 'tips']
	]);

// Generate favicons if they don't exist
$iconsDir = __DIR__ . '/../public/icons';
if (!file_exists($iconsDir . '/favicon.ico')) {
	try {
		$generated = $seo->favicons()->generate(
			__DIR__ . '/../assets/logo-1024x1024.png',
			[
				'output_dir' => $iconsDir,
				'quality' => 90
			]
		);
		echo "Generated " . count($generated) . " favicon files\n";
	} catch (Exception $e) {
		echo "Could not generate favicons: " . $e->getMessage() . "\n";
	}
}

// Add article schema
$articleSchema = $seo->schema()->article([
	'headline' => '10 Tips for Better SEO in 2024',
	'description' => 'Learn the latest SEO strategies and techniques.',
	'image' => 'https://example.com/blog/seo-tips.jpg',
	'datePublished' => '2024-01-15T10:00:00Z',
	'dateModified' => '2024-01-20T14:30:00Z',
	'author' => [
		'name' => 'Jane Smith',
		'url' => 'https://example.com/authors/jane-smith'
	],
	'publisher' => [
		'name' => 'Example Blog',
		'logo' => 'https://example.com/logo.png'
	]
]);
$seo->schema()->add($articleSchema);

// Example 4: Event page with themed favicons
$seo->setCommon([
	'title' => 'SEO Conference 2024 - Register Now',
	'description' => 'Join us for the biggest SEO event of the year.',
	'image' => 'https://example.com/events/seo-conference-2024.jpg',
	'url' => 'https://example.com/events/seo-conference-2024'
]);

// Set event-specific theme color
$seo->favicons()
	->setThemeColor('#FF6B6B')
	->setSafariPinnedTab('/icons/safari-pinned-tab.svg', '#FF6B6B');

$eventSchema = $seo->schema()->event([
	'name' => 'SEO Conference 2024',
	'description' => 'Join us for the biggest SEO event of the year.',
	'startDate' => '2024-06-15T09:00:00Z',
	'endDate' => '2024-06-17T18:00:00Z',
	'location' => [
		'name' => 'Convention Center',
		'address' => [
			'streetAddress' => '123 Main St',
			'addressLocality' => 'San Francisco',
			'addressRegion' => 'CA',
			'postalCode' => '94105',
			'addressCountry' => 'US'
		]
	],
	'organizer' => 'SEO Experts Inc.'
]);
$seo->schema()->add($eventSchema);

// Example 5: Local Business with custom favicons
$businessSchema = $seo->schema()->localBusiness([
	'name' => 'Joe\'s Pizza',
	'image' => 'https://joespizza.com/storefront.jpg',
	'description' => 'Best pizza in town since 1970',
	'telephone' => '+1-555-123-4567',
	'address' => [
		'streetAddress' => '456 Oak Street',
		'addressLocality' => 'New York',
		'addressRegion' => 'NY',
		'postalCode' => '10001',
		'addressCountry' => 'US'
	],
	'priceRange' => '$'
]);
$seo->schema()->add($businessSchema);

// Restaurant-themed favicons
$seo->favicons()
	->setThemeColor('#D32F2F')
	->setMsTileColor('#D32F2F')
	->setMsTileImage('/icons/mstile-150x150.webp');

// Example 6: FAQ Page with Progressive Web App setup
$seo->meta()
	->setTitle('Frequently Asked Questions')
	->setDescription('Find answers to common questions about our services.');

// PWA configuration with favicons
$pwaConfig = [
	'name' => 'FAQ Center',
	'short_name' => 'FAQ',
	'display' => 'standalone',
	'orientation' => 'portrait',
	'theme_color' => '#673AB7',
	'background_color' => '#ffffff',
	'start_url' => '/faq?source=pwa'
];

// Generate manifest with favicon configuration
$manifestContent = $seo->favicons()->generateManifest($pwaConfig);
file_put_contents(__DIR__ . '/../public/manifest.json', $manifestContent);

$faqSchema = $seo->schema()->faqPage([
	[
		'question' => 'What is SEO?',
		'answer' => 'SEO stands for Search Engine Optimization...'
	],
	[
		'question' => 'How long does SEO take?',
		'answer' => 'SEO is a long-term strategy that typically takes 3-6 months...'
	]
]);
$seo->schema()->add($faqSchema);

// Example 7: Recipe with mobile-optimized favicons
$seo->meta()
	->setTitle('Chocolate Chip Cookies Recipe')
	->setDescription('Delicious homemade chocolate chip cookies in 30 minutes.');

// Mobile web app capable for recipe saving
$seo->favicons()
	->setThemeColor('#795548');

// If favicons need generation from recipe photo
if (!file_exists($iconsDir . '/android-chrome-192x192.webp')) {
	try {
		// Use high-quality recipe photo for favicons
		$seo->favicons()->generate(
			__DIR__ . '/../assets/cookie-photo-square.jpg',
			[
				'output_dir' => $iconsDir,
				'quality' => 85,
				'types' => ['android', 'apple-touch'] // Mobile-focused
			]
		);
	} catch (Exception $e) {
		// Fall back to default favicons
		echo "Using default favicons: " . $e->getMessage() . "\n";
	}
}

$recipeSchema = $seo->schema()->recipe([
	'name' => 'Chocolate Chip Cookies',
	'image' => 'https://example.com/cookies.jpg',
	'description' => 'Delicious homemade chocolate chip cookies',
	'recipeYield' => '24 cookies',
	'totalTime' => 'PT30M',
	'cookTime' => 'PT15M',
	'prepTime' => 'PT15M',
	'ingredients' => [
		'2 cups all-purpose flour',
		'1 cup butter',
		'1 cup chocolate chips'
	],
	'instructions' => [
		'Preheat oven to 375°F',
		'Mix ingredients',
		'Bake for 12-15 minutes'
	]
]);
$seo->schema()->add($recipeSchema);

// Example 8: Complete validation with favicons
$metaErrors = $seo->meta()->validate();
$ogErrors = $seo->openGraph()->validate();
$twitterErrors = $seo->twitter()->validate();

// Check if favicons are properly configured
$faviconData = $seo->favicons()->toArray();
if (empty($faviconData['favicon'])) {
	echo "Warning: No favicon.ico configured\n";
}
if (empty($faviconData['manifest'])) {
	echo "Warning: No web manifest configured\n";
}

if (!empty($metaErrors) || !empty($ogErrors) || !empty($twitterErrors)) {
	// Handle validation errors
	echo "Validation errors found:\n";
	print_r(array_merge($metaErrors, $ogErrors, $twitterErrors));
}

// Render all SEO tags including favicons
echo $seo->render();

// Or render individually
echo $seo->favicons()->render() . "\n";
echo $seo->meta()->render() . "\n";
echo $seo->openGraph()->render() . "\n";
echo $seo->twitter()->render() . "\n";
echo $seo->schema()->render() . "\n";

// Get as array (useful for APIs or further processing)
$seoData = $seo->toArray();
// Now includes 'favicons' key with all favicon data
echo json_encode($seoData, JSON_PRETTY_PRINT);