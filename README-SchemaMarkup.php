<?php

use Yohns\SEO\SchemaMarkup;

$schema = new SchemaMarkup();

// ============================================================================
// 1. ORGANIZATION SCHEMA
// ============================================================================
$organizationSchema = $schema->organization([
	'name' => 'Acme Corporation',
	'url' => 'https://acme.com',
	'logo' => 'https://acme.com/logo.png',
	'description' => 'Leading provider of innovative business solutions',
	'telephone' => '+1-555-123-4567',
	'email' => 'info@acme.com',
	'address' => [
		'streetAddress' => '123 Business Ave',
		'addressLocality' => 'New York',
		'addressRegion' => 'NY',
		'postalCode' => '10001',
		'addressCountry' => 'US'
	]
]);
$schema->add($organizationSchema);

// ============================================================================
// 2. PERSON SCHEMA
// ============================================================================
$personSchema = $schema->person([
	'name' => 'Jane Doe',
	'url' => 'https://janedoe.com',
	'image' => 'https://janedoe.com/photo.jpg',
	'description' => 'Expert software engineer and tech consultant',
	'jobTitle' => 'Senior Software Engineer',
	'telephone' => '+1-555-555-5555',
	'email' => 'jane@example.com',
	'birthDate' => '1985-05-15'
]);
$schema->add($personSchema);

// ============================================================================
// 3. PRODUCT SCHEMA
// ============================================================================
$productSchema = $schema->product([
	'name' => 'Smartphone X Pro',
	'image' => 'https://shop.com/phone.jpg',
	'description' => 'Latest flagship smartphone with advanced features',
	'brand' => 'TechBrand',
	'model' => 'X-Pro-2024',
	'price' => '999.99',
	'currency' => 'USD',
	'review' => [
		'reviewBody' => 'Amazing phone with incredible camera quality!',
		'ratingValue' => '5',
		'bestRating' => '5',
		'worstRating' => '1',
		'author' => 'John Customer'
	]
]);
$schema->add($productSchema);

// ============================================================================
// 4. ARTICLE SCHEMA
// ============================================================================
$articleSchema = $schema->article([
	'headline' => 'Breaking: Revolutionary Tech Announcement Changes Industry',
	'description' => 'Tech giant announces groundbreaking product that will reshape the market',
	'image' => 'https://news.com/article-image.jpg',
	'datePublished' => '2024-01-20T10:00:00Z',
	'dateModified' => '2024-01-20T14:30:00Z',
	'author' => [
		'name' => 'Tech Reporter',
		'url' => 'https://news.com/authors/tech-reporter'
	],
	'publisher' => [
		'name' => 'Tech News Daily',
		'logo' => 'https://news.com/logo.png'
	]
]);
$schema->add($articleSchema);

// ============================================================================
// 5. EVENT SCHEMA
// ============================================================================
$eventSchema = $schema->event([
	'name' => 'Web Development Conference 2024',
	'description' => 'Annual conference for web developers featuring latest trends and technologies',
	'startDate' => '2024-06-15T09:00:00Z',
	'endDate' => '2024-06-17T18:00:00Z',
	'location' => [
		'name' => 'San Francisco Convention Center',
		'address' => [
			'streetAddress' => '456 Event Plaza',
			'addressLocality' => 'San Francisco',
			'addressRegion' => 'CA',
			'postalCode' => '94105',
			'addressCountry' => 'US'
		]
	],
	'organizer' => [
		'name' => 'Dev Events Inc.',
		'url' => 'https://devevents.com'
	],
	'performer' => [
		'name' => 'Sarah Johnson',
		'jobTitle' => 'Keynote Speaker'
	]
]);
$schema->add($eventSchema);

// ============================================================================
// 6. RECIPE SCHEMA
// ============================================================================
$recipeSchema = $schema->recipe([
	'name' => 'Chocolate Chip Cookies',
	'image' => 'https://recipes.com/chocolate-chip-cookies.jpg',
	'description' => 'Delicious homemade chocolate chip cookies that are crispy outside and chewy inside',
	'recipeYield' => '24 cookies',
	'totalTime' => 'PT45M',
	'cookTime' => 'PT12M',
	'prepTime' => 'PT15M',
	'ingredients' => [
		'2 1/4 cups all-purpose flour',
		'1 tsp baking soda',
		'1 tsp salt',
		'1 cup butter, softened',
		'3/4 cup granulated sugar',
		'3/4 cup packed brown sugar',
		'2 large eggs',
		'2 tsp vanilla extract',
		'2 cups chocolate chips'
	],
	'instructions' => [
		'Preheat oven to 375°F (190°C)',
		'Mix flour, baking soda and salt in bowl',
		'Beat butter and sugars until creamy',
		'Add eggs and vanilla, beat well',
		'Gradually blend in flour mixture',
		'Stir in chocolate chips',
		'Drop rounded tablespoons onto ungreased cookie sheets',
		'Bake 9 to 11 minutes or until golden brown'
	],
	'nutrition' => [
		'calories' => '180'
	]
]);
$schema->add($recipeSchema);

// ============================================================================
// 7. REVIEW SCHEMA (Standalone)
// ============================================================================
$reviewSchema = $schema->review([
	'reviewBody' => 'Excellent service and outstanding product quality. Highly recommend to anyone looking for reliable solutions.',
	'ratingValue' => '5',
	'bestRating' => '5',
	'worstRating' => '1',
	'author' => [
		'name' => 'Maria Rodriguez',
		'url' => 'https://reviewers.com/maria'
	],
	'itemReviewed' => [
		'@type' => 'Product',
		'name' => 'Premium Service Package'
	]
]);
$schema->add($reviewSchema);

// ============================================================================
// 8. VIDEO SCHEMA
// ============================================================================
$videoSchema = $schema->video([
	'name' => 'How to Build a Modern Web Application',
	'description' => 'Complete tutorial on building scalable web applications using modern frameworks',
	'thumbnailUrl' => 'https://videos.com/thumbnail.jpg',
	'uploadDate' => '2024-01-15T10:00:00Z',
	'duration' => 'PT25M30S',
	'contentUrl' => 'https://videos.com/tutorial.mp4',
	'embedUrl' => 'https://videos.com/embed/tutorial',
	'publisher' => [
		'name' => 'Tech Education Hub',
		'logo' => 'https://techedu.com/logo.png'
	]
]);
$schema->add($videoSchema);

// ============================================================================
// 9. BOOK SCHEMA
// ============================================================================
$bookSchema = $schema->book([
	'name' => 'Modern Web Development: Complete Guide',
	'author' => [
		'name' => 'Alex Thompson',
		'url' => 'https://alexthompson.dev'
	],
	'isbn' => '978-0123456789',
	'description' => 'Comprehensive guide covering all aspects of modern web development',
	'image' => 'https://books.com/cover.jpg',
	'publisher' => [
		'name' => 'Tech Publishers Inc.',
		'url' => 'https://techpublishers.com'
	],
	'datePublished' => '2024-01-01'
]);
$schema->add($bookSchema);

// ============================================================================
// 10. COURSE SCHEMA
// ============================================================================
$courseSchema = $schema->course([
	'name' => 'Advanced JavaScript Programming',
	'description' => 'Master advanced JavaScript concepts including ES6+, async programming, and frameworks',
	'provider' => [
		'name' => 'Online Learning Academy',
		'url' => 'https://onlineacademy.com'
	],
	'educationalLevel' => 'Advanced',
	'courseMode' => 'Online'
]);
$schema->add($courseSchema);

// ============================================================================
// 11. LOCAL BUSINESS SCHEMA
// ============================================================================
$localBusinessSchema = $schema->localBusiness([
	'name' => 'Joe\'s Pizza Restaurant',
	'image' => 'https://joespizza.com/restaurant.jpg',
	'description' => 'Authentic Italian pizza made with fresh ingredients since 1985',
	'telephone' => '+1-555-PIZZA-99',
	'address' => [
		'streetAddress' => '789 Main Street',
		'addressLocality' => 'Downtown',
		'addressRegion' => 'NY',
		'postalCode' => '10012',
		'addressCountry' => 'US'
	],
	'openingHours' => [
		[
			'@type' => 'OpeningHoursSpecification',
			'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
			'opens' => '11:00',
			'closes' => '22:00'
		],
		[
			'@type' => 'OpeningHoursSpecification',
			'dayOfWeek' => ['Saturday', 'Sunday'],
			'opens' => '12:00',
			'closes' => '23:00'
		]
	],
	'priceRange' => '$$'
]);
$schema->add($localBusinessSchema);

// ============================================================================
// 12. FAQ PAGE SCHEMA
// ============================================================================
$faqPageSchema = $schema->faqPage([
	[
		'question' => 'What payment methods do you accept?',
		'answer' => 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, Apple Pay, Google Pay, and bank transfers.'
	],
	[
		'question' => 'Do you offer international shipping?',
		'answer' => 'Yes, we ship to over 50 countries worldwide. Shipping costs and delivery times vary by destination.'
	],
	[
		'question' => 'What is your return policy?',
		'answer' => 'We offer a 30-day money-back guarantee on all products. Items must be returned in original condition with all packaging.'
	],
	[
		'question' => 'How long does processing take?',
		'answer' => 'Orders are typically processed within 1-2 business days. You will receive tracking information once your order ships.'
	],
	[
		'question' => 'Do you offer customer support?',
		'answer' => 'Yes, our customer support team is available Monday-Friday 9AM-6PM EST via email, phone, and live chat.'
	]
]);
$schema->add($faqPageSchema);

// ============================================================================
// 13. SOFTWARE APPLICATION SCHEMA
// ============================================================================
$softwareApplicationSchema = $schema->softwareApplication([
	'name' => 'ProductivityMax Pro',
	'operatingSystem' => 'Windows, macOS, Linux',
	'category' => 'ProductivityApplication',
	'version' => '3.2.1',
	'description' => 'Advanced productivity suite for professionals and teams',
	'offers' => [
		'price' => '29.99',
		'currency' => 'USD'
	],
	'aggregateRating' => [
		'value' => '4.8',
		'count' => '1247'
	]
]);
$schema->add($softwareApplicationSchema);

// ============================================================================
// 14. CUSTOM SCHEMA (using create method)
// ============================================================================
$jobPostingSchema = $schema->create('JobPosting', [
	'title' => 'Senior Full Stack Developer',
	'description' => 'Join our team to build next-generation web applications using cutting-edge technologies',
	'hiringOrganization' => [
		'@type' => 'Organization',
		'name' => 'Tech Innovations Inc.',
		'sameAs' => 'https://techinnovations.com'
	],
	'jobLocation' => [
		'@type' => 'Place',
		'address' => [
			'@type' => 'PostalAddress',
			'addressLocality' => 'San Francisco',
			'addressRegion' => 'CA',
			'addressCountry' => 'US'
		]
	],
	'baseSalary' => [
		'@type' => 'MonetaryAmount',
		'currency' => 'USD',
		'value' => [
			'@type' => 'QuantitativeValue',
			'minValue' => 120000,
			'maxValue' => 180000,
			'unitText' => 'YEAR'
		]
	],
	'employmentType' => 'FULL_TIME',
	'datePosted' => '2024-01-15',
	'validThrough' => '2024-03-15'
]);
$schema->add($jobPostingSchema);

// ============================================================================
// 15. BREADCRUMB LIST SCHEMA (using create method)
// ============================================================================
$breadcrumbListSchema = $schema->create('BreadcrumbList', [
	'itemListElement' => [
		[
			'@type' => 'ListItem',
			'position' => 1,
			'name' => 'Home',
			'item' => 'https://example.com'
		],
		[
			'@type' => 'ListItem',
			'position' => 2,
			'name' => 'Products',
			'item' => 'https://example.com/products'
		],
		[
			'@type' => 'ListItem',
			'position' => 3,
			'name' => 'Electronics',
			'item' => 'https://example.com/products/electronics'
		],
		[
			'@type' => 'ListItem',
			'position' => 4,
			'name' => 'Smartphones',
			'item' => 'https://example.com/products/electronics/smartphones'
		],
		[
			'@type' => 'ListItem',
			'position' => 5,
			'name' => 'Smartphone X Pro'
		]
	]
]);
$schema->add($breadcrumbListSchema);

// ============================================================================
// OUTPUT ALL SCHEMAS
// ============================================================================

// Method 1: Render all as HTML
echo $schema->render();

// Method 2: Get as array for processing
$allSchemas = $schema->toArray();

// Method 3: Clear all and start fresh
$schema->clear();

// Method 4: Validate individual schema
$validatedSchema = $schema->validate($productSchema);

/**
 * USAGE WITH SEOMANAGER
 */
use Yohns\SEO\SEOManager;

$seo = new SEOManager();

// Add individual schemas
$seo->schema()->add($organizationSchema);
$seo->schema()->add($productSchema);
$seo->schema()->add($articleSchema);

// Or create and add in one step
$eventSchema = $seo->schema()->event([
	'name' => 'Tech Meetup',
	'startDate' => '2024-02-15T19:00:00Z'
]);
$seo->schema()->add($eventSchema);

// Render all SEO including schemas
echo $seo->render();
