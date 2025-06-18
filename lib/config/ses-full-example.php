<?php
// File: lib/config/seo.php

return [
	'meta' => [
		'defaults' => [
			// Basic meta tags
			'title' => 'Default Page Title',
			'description' => 'Default meta description for the website',
			'keywords' => 'default, keywords, here',
			'viewport' => 'width=device-width, initial-scale=1',
			'robots' => 'index, follow',
			'canonical' => null, // Set per page
			'favicon' => '/favicon.ico',

			// Custom meta tags
			'custom' => [
				'author' => 'Your Name',
				'generator' => 'Your CMS/Framework',
				'theme-color' => '#4F46E5',
				'color-scheme' => 'light dark',
				'format-detection' => 'telephone=no',
				'google-site-verification' => null,
				'msvalidate.01' => null, // Bing verification
				'yandex-verification' => null,
				'pinterest-site-verification' => null
			]
		]
	],

	'opengraph' => [
		'defaults' => [
			// Required OG tags
			'type' => 'website',
			'title' => null, // Falls back to meta title
			'url' => null, // Set per page
			'image' => 'https://yoursite.com/default-og.jpg',

			// Recommended OG tags
			'description' => null, // Falls back to meta description
			'site_name' => 'Your Site Name',
			'locale' => 'en_US',
			'locale:alternate' => ['es_ES', 'fr_FR'], // Alternative locales

			// Optional OG tags
			'image:width' => 1200,
			'image:height' => 630,
			'image:alt' => 'Default image description',
			'image:type' => 'image/jpeg',
			'image:secure_url' => null,

			// Video properties (if applicable)
			'video' => null,
			'video:width' => null,
			'video:height' => null,
			'video:type' => null,
			'video:secure_url' => null,

			// Audio properties (if applicable)
			'audio' => null,
			'audio:type' => null,
			'audio:secure_url' => null,

			// Article properties (for article type)
			'article:author' => null,
			'article:published_time' => null,
			'article:modified_time' => null,
			'article:expiration_time' => null,
			'article:section' => null,
			'article:tag' => [],

			// Profile properties (for profile type)
			'profile:first_name' => null,
			'profile:last_name' => null,
			'profile:username' => null,
			'profile:gender' => null,

			// Book properties (for book type)
			'book:author' => null,
			'book:isbn' => null,
			'book:release_date' => null,
			'book:tag' => [],

			// Music properties
			'music:duration' => null,
			'music:album' => null,
			'music:album:disc' => null,
			'music:album:track' => null,
			'music:musician' => null,
			'music:song' => null,
			'music:song:disc' => null,
			'music:song:track' => null,
			'music:release_date' => null,
			'music:creator' => null
		]
	],

	'twitter' => [
		'defaults' => [
			// Card type
			'card' => 'summary_large_image', // summary, summary_large_image, app, player

			// Required for all cards
			'site' => '@yoursite', // Site's Twitter username
			'creator' => '@defaultcreator', // Content creator's Twitter username

			// Content fields
			'title' => null, // Falls back to OG/meta title
			'description' => null, // Falls back to OG/meta description
			'image' => null, // Falls back to OG image
			'image:alt' => null, // Alt text for image

			// Player card properties
			'player' => null, // HTTPS URL to player iframe
			'player:width' => null,
			'player:height' => null,
			'player:stream' => null, // URL to raw stream
			'player:stream:content_type' => null,

			// App card properties
			'app:id:iphone' => null,
			'app:id:ipad' => null,
			'app:id:googleplay' => null,
			'app:url:iphone' => null,
			'app:url:ipad' => null,
			'app:url:googleplay' => null,
			'app:name:iphone' => null,
			'app:name:ipad' => null,
			'app:name:googleplay' => null,
			'app:country' => null
		]
	],

	'schema' => [
		'defaults' => [
			// Default context
			'context' => 'https://schema.org',

			// Organization schema (often used as publisher)
			'organization' => [
				'@type' => 'Organization',
				'name' => 'Your Organization Name',
				'alternateName' => 'Your Org Alternate Name',
				'url' => 'https://yoursite.com',
				'logo' => 'https://yoursite.com/logo.png',
				'description' => 'Organization description',
				'email' => 'info@yoursite.com',
				'telephone' => '+1-555-123-4567',
				'faxNumber' => '+1-555-123-4568',
				'address' => [
					'@type' => 'PostalAddress',
					'streetAddress' => '123 Main Street',
					'addressLocality' => 'Your City',
					'addressRegion' => 'State/Province',
					'postalCode' => '12345',
					'addressCountry' => 'US'
				],
				'sameAs' => [
					'https://facebook.com/yourpage',
					'https://twitter.com/yourhandle',
					'https://linkedin.com/company/yourcompany',
					'https://instagram.com/yourhandle',
					'https://youtube.com/c/yourchannel'
				],
				'founder' => null,
				'foundingDate' => null,
				'employees' => null,
				'slogan' => null,
				'taxID' => null,
				'vatID' => null
			],

			// WebSite schema (for SiteLinks Search Box)
			'website' => [
				'@type' => 'WebSite',
				'name' => 'Your Website Name',
				'alternateName' => null,
				'url' => 'https://yoursite.com',
				'potentialAction' => [
					'@type' => 'SearchAction',
					'target' => 'https://yoursite.com/search?q={search_term_string}',
					'query-input' => 'required name=search_term_string'
				]
			],

			// Default values for common properties
			'dateFormat' => 'Y-m-d\TH:i:sP', // ISO 8601
			'priceFormat' => [
				'currency' => 'USD',
				'locale' => 'en_US'
			],

			// Default image object structure
			'imageObject' => [
				'@type' => 'ImageObject',
				'url' => null,
				'width' => null,
				'height' => null,
				'caption' => null
			],

			// Default video object structure
			'videoObject' => [
				'@type' => 'VideoObject',
				'name' => null,
				'description' => null,
				'thumbnailUrl' => null,
				'uploadDate' => null,
				'duration' => null, // ISO 8601 duration
				'contentUrl' => null,
				'embedUrl' => null
			],

			// Default rating structure
			'rating' => [
				'@type' => 'Rating',
				'ratingValue' => null,
				'bestRating' => '5',
				'worstRating' => '1'
			],

			// Default review structure
			'review' => [
				'@type' => 'Review',
				'reviewRating' => null, // Uses rating structure
				'author' => null,
				'datePublished' => null,
				'reviewBody' => null
			],

			// Default offer structure
			'offer' => [
				'@type' => 'Offer',
				'price' => null,
				'priceCurrency' => 'USD',
				'availability' => 'https://schema.org/InStock',
				'url' => null,
				'priceValidUntil' => null,
				'seller' => null // Can reference organization
			]
		]
	],

	'favicons' => [
		// Base paths
		'base_path' => __DIR__ . '/../../public/icons',
		'base_url' => '/icons',

		// App information
		'app_name' => 'Your App Name',
		'app_short_name' => 'App',
		'mobile_web_app_capable' => true,
		'apple_mobile_web_app_capable' => true,
		'apple_mobile_web_app_status_bar_style' => 'default', // default, black, black-translucent

		// Colors
		'theme-color' => '#4F46E5',
		'ms-tile-color' => '#ffffff',
		'safari-pinned-tab-color' => '#5bbad5',

		// Default icons (if already generated)
		'icons' => [
			'favicon' => '/favicon.ico',
			'manifest' => '/manifest.json',
			'browserconfig' => '/browserconfig.xml',

			// PNG/WebP icons by size
			'png' => [
				'16x16' => '/icons/favicon-16x16.webp',
				'32x32' => '/icons/favicon-32x32.webp',
				'48x48' => '/icons/favicon-48x48.webp'
			],

			// Apple touch icons
			'apple-touch' => [
				'152x152' => '/icons/apple-touch-icon-152x152.webp',
				'167x167' => '/icons/apple-touch-icon-167x167.webp',
				'180x180' => '/icons/apple-touch-icon-180x180.webp'
			],

			// Microsoft tile
			'mstile' => '/icons/mstile-150x150.webp',

			// Safari pinned tab (SVG)
			'safari-pinned-tab' => [
				'path' => '/icons/safari-pinned-tab.svg',
				'color' => '#5bbad5'
			]
		],

		// Web App Manifest settings
		'manifest' => [
			'name' => 'Your Application Name',
			'short_name' => 'YourApp',
			'description' => 'Your app description',
			'start_url' => '/?source=pwa',
			'display' => 'standalone', // fullscreen, standalone, minimal-ui, browser
			'orientation' => 'portrait', // any, natural, landscape, portrait
			'theme_color' => '#4F46E5',
			'background_color' => '#ffffff',
			'lang' => 'en-US',
			'dir' => 'ltr', // ltr, rtl, auto
			'categories' => ['productivity'], // https://github.com/w3c/manifest/wiki/Categories
			'iarc_rating_id' => null, // International Age Rating Coalition ID
			'prefer_related_applications' => false,
			'related_applications' => [], // Native app alternatives
			'scope' => '/', // URL scope for the PWA
			'serviceworker' => [
				'src' => '/sw.js',
				'scope' => '/',
				'update_via_cache' => 'none'
			],
			'screenshots' => [], // App store screenshots
			'shortcuts' => [] // Quick actions
		],

		// Generation settings - Example with GD (default)
		'generation' => [
			'method' => 'gd', // 'gd' or 'imagemagick'
			'quality' => 90, // WebP quality (1-100)
			'min_source_size' => 512, // Minimum acceptable source size
			'recommended_source_size' => 1024, // Recommended source size
			'auto_generate' => true, // Auto-generate missing icons
			'overwrite_existing' => false, // Don't overwrite existing files
			'formats' => ['webp', 'ico'] // Output formats
		]

		// Generation settings - Example with ImageMagick
		// 'generation' => [
		// 	'method' => 'imagemagick',
		// 	'imagick_path' => '/usr/bin/convert', // Full path to ImageMagick convert command
		// 	'quality' => 95, // Can use higher quality with ImageMagick
		// 	'min_source_size' => 512,
		// 	'recommended_source_size' => 1024,
		// 	'auto_generate' => true,
		// 	'overwrite_existing' => false,
		// 	'formats' => ['webp', 'ico'],
		// 	// ImageMagick specific options
		// 	'sharpen' => true, // Apply sharpening after resize
		// 	'strip_metadata' => true, // Remove EXIF data
		// 	'compression' => 'lossless' // lossless or lossy
		// 	// Common paths:
		// 	// Linux: '/usr/bin/convert' or '/usr/local/bin/convert'
		// 	// Windows: 'C:\\Program Files\\ImageMagick-7.1.0-Q16\\magick.exe'
		// 	// macOS: '/opt/homebrew/bin/convert' (Homebrew) or '/usr/local/bin/convert'
		// ]
	]
];