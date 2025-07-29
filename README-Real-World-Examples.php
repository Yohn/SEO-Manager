<?php

require_once 'vendor/autoload.php';

use Yohns\Core\Config;
use Yohns\SEO\SEOManager;

// Initialize SEOManager with optional configuration
$config = new Config(__DIR__ . '/config');
$seo = new SEOManager($config, 'seo');

// Alternative: Initialize without configuration
// $seo = new SEOManager();

/**
 * COMPONENT ACCESS METHODS
 * Each component can be accessed through dedicated methods
 */

// 1. FAVICONS COMPONENT
$seo->favicons()
	->setThemeColor('#4F46E5')
	->setManifest('/manifest.json')
	->setMsTileColor('#2196F3');

// Generate favicons from source image
try {
	$generatedIcons = $seo->favicons()->generate(
		__DIR__ . '/assets/logo-512x512.png',
		[
			'output_dir' => __DIR__ . '/public/icons',
			'quality' => 90
		]
	);
} catch (Exception $e) {
	echo "Favicon generation failed: " . $e->getMessage();
}

// 2. META TAGS COMPONENT
$seo->meta()
	->setTitle('My Amazing Website - Home')
	->setDescription('Discover innovative solutions that transform your business')
	->setKeywords(['business', 'innovation', 'solutions'])
	->setCanonical('https://example.com')
	->setRobots('index, follow')
	->setViewport('width=device-width, initial-scale=1')
	->setFavicon('/favicon.ico');

// 3. OPEN GRAPH COMPONENT
$seo->openGraph()
	->setTitle('My Amazing Website')
	->setDescription('Transform your business with our solutions')
	->setType('website')
	->setUrl('https://example.com')
	->setImage('https://example.com/og-image.jpg', 1200, 630, 'Website Preview')
	->setSiteName('My Company')
	->setLocale('en_US');

// For article pages
$seo->openGraph()
	->setType('article')
	->setArticle([
		'author' => 'John Doe',
		'published_time' => '2024-01-15T10:00:00Z',
		'modified_time' => '2024-01-20T14:30:00Z',
		'section' => 'Technology',
		'tag' => ['seo', 'web', 'development']
	]);

// 4. TWITTER CARDS COMPONENT
$seo->twitter()
	->setCard('summary_large_image')
	->setTitle('My Amazing Website')
	->setDescription('Transform your business with our solutions')
	->setImage('https://example.com/twitter-card.jpg')
	->setSite('@mycompany')
	->setCreator('@johndoe');

// 5. SCHEMA MARKUP COMPONENT
// Organization schema
$orgSchema = $seo->schema()->organization([
	'name' => 'My Company',
	'url' => 'https://example.com',
	'logo' => 'https://example.com/logo.png',
	'description' => 'Leading provider of business solutions',
	'address' => [
		'streetAddress' => '123 Business St',
		'addressLocality' => 'Business City',
		'addressRegion' => 'BC',
		'postalCode' => '12345',
		'addressCountry' => 'US'
	],
	'contactPoint' => [
		'telephone' => '+1-555-123-4567',
		'contactType' => 'customer service'
	]
]);

// Product schema
$productSchema = $seo->schema()->product([
	'name' => 'Amazing Product',
	'image' => 'https://example.com/product.jpg',
	'description' => 'The best product for your needs',
	'brand' => 'My Company',
	'model' => 'AP-2024',
	'price' => '99.99',
	'currency' => 'USD',
	'availability' => 'InStock',
	'review' => [
		'reviewBody' => 'Excellent product, highly recommended!',
		'ratingValue' => '5',
		'bestRating' => '5',
		'worstRating' => '1',
		'author' => 'Happy Customer'
	]
]);

// Article schema
$articleSchema = $seo->schema()->article([
	'headline' => 'How to Use SEO Manager',
	'description' => 'Complete guide to implementing SEO',
	'author' => 'John Doe',
	'datePublished' => '2024-01-15',
	'dateModified' => '2024-01-20',
	'image' => 'https://example.com/article-image.jpg',
	'publisher' => [
		'name' => 'My Company',
		'logo' => 'https://example.com/logo.png'
	]
]);

// Add schemas to the manager
$seo->schema()->add($orgSchema);
$seo->schema()->add($productSchema);
$seo->schema()->add($articleSchema);

/**
 * UNIFIED METHODS
 */

// Set common properties across all components
$seo->setCommon([
	'title' => 'My Page Title',
	'description' => 'Page description that works across all platforms',
	'image' => 'https://example.com/share-image.jpg',
	'url' => 'https://example.com/current-page'
]);

// This automatically sets:
// - meta()->setTitle() and setDescription() and setCanonical()
// - openGraph()->setTitle(), setDescription(), setImage(), setUrl()
// - twitter()->setTitle(), setDescription(), setImage()

/**
 * OUTPUT METHODS
 */

// Method 1: Render all components as HTML
echo $seo->render();
// Outputs all favicons, meta tags, Open Graph, Twitter Cards, and Schema markup

// Method 2: Render individual components
echo $seo->favicons()->render();
echo $seo->meta()->render();
echo $seo->openGraph()->render();
echo $seo->twitter()->render();
echo $seo->schema()->render();

// Method 3: Get data as arrays
$allData = $seo->toArray();
/*
Returns:
[
	'favicons' => [...],
	'meta' => [...],
	'opengraph' => [...],
	'twitter' => [...],
	'schema' => [...]
]
*/

$metaData = $seo->meta()->toArray();
$ogData = $seo->openGraph()->toArray();
$twitterData = $seo->twitter()->toArray();
$schemaData = $seo->schema()->toArray();
$faviconData = $seo->favicons()->toArray();

/**
 * CHAINING EXAMPLES
 */

// Example 1: Blog post page setup
$seo->setCommon([
		'title' => 'How to Master SEO in 2024',
		'description' => 'Complete guide to modern SEO techniques and best practices',
		'image' => 'https://example.com/blog/seo-guide.jpg',
		'url' => 'https://example.com/blog/seo-guide'
	])
	->meta()->setKeywords(['seo', 'guide', '2024', 'marketing'])
	->meta()->setRobots('index, follow');

$seo->openGraph()
	->setType('article')
	->setSiteName('My Blog')
	->setArticle([
		'author' => 'SEO Expert',
		'published_time' => date('c'),
		'section' => 'SEO'
	]);

$seo->twitter()
	->setCard('summary_large_image')
	->setCreator('@seoexpert');

// Example 2: E-commerce product page
$seo->setCommon([
		'title' => 'Premium Widget - Buy Online',
		'description' => 'High-quality premium widget with 5-year warranty',
		'image' => 'https://example.com/products/premium-widget.jpg',
		'url' => 'https://example.com/products/premium-widget'
	])
	->meta()->setKeywords(['widget', 'premium', 'buy online', 'quality']);

$seo->openGraph()->setType('product');

$productSchema = $seo->schema()->product([
	'name' => 'Premium Widget',
	'price' => '199.99',
	'currency' => 'USD',
	'brand' => 'WidgetCorp',
	'availability' => 'InStock'
]);
$seo->schema()->add($productSchema);

/**
 * COMPLETE USAGE IN HTML TEMPLATE
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php echo $seo->render(); ?>
	
	<!-- Additional head content -->
</head>
<body>
	<!-- Page content -->
</body>
</html>
