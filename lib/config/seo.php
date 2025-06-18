<?php
// File: lib/config/seo.php

return [
	'meta' => [
		'defaults' => [
			'viewport' => 'width=device-width, initial-scale=1',
			'robots' => 'index, follow',
			'keywords' => 'default, keywords, here'
		]
	],

	'opengraph' => [
		'defaults' => [
			'type' => 'website',
			'site_name' => 'Your Site Name',
			'locale' => 'en_US'
		]
	],

	'twitter' => [
		'defaults' => [
			'card' => 'summary_large_image',
			'site' => '@yoursite'
		]
	],

	'schema' => [
		'defaults' => [
			'organization' => [
				'name' => 'Your Organization Name',
				'url' => 'https://yoursite
				.com',
				'logo' => 'https://yoursite.com/logo.png'
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

		// Default icons (if already generated)
		'icons' => [
			'favicon' => '/favicon.ico',
			'theme-color' => '#4F46E5',
			'ms-tile-color' => '#ffffff',
			'manifest' => '/manifest.json'
		],

		// Generation settings - Example with GD (default)
		'generation' => [
			'method' => 'gd', // 'gd' or 'imagemagick'
			// No additional config needed for GD
		]

		// Generation settings - Example with ImageMagick
		// 'generation' => [
		// 	'method' => 'imagemagick',
		// 	'imagick_path' => '/usr/bin/convert', // Full path to ImageMagick convert command
		// 	// Common paths:
		// 	// Linux: '/usr/bin/convert' or '/usr/local/bin/convert'
		// 	// Windows: 'C:\\Program Files\\ImageMagick-7.1.0-Q16\\magick.exe'
		// 	// macOS: '/opt/homebrew/bin/convert' (Homebrew) or '/usr/local/bin/convert'
		// ]
	]
];