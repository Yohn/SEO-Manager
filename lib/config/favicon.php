<?php
// File: config/favicon.php
$title = 'My Amazing Website';
$description = 'Discover innovative solutions and services that transform your business.';
$keywords = 'favicon, seo, web development';

// 512x512 image URL that will become your favicon in all sizes.
$image = 'https://yoursite.com/Logo-512x.jpg';
$url = 'https://yoursite.com';

return [
	'favicons' => [
		// Base paths
		'base_path' => __DIR__ . '/../../public/assets/imgs/icons',
		'base_url' => '/assets/imgs/icons',

		// App information
		'app_name' => $title,
		'app_short_name' => 'VI',
		'mobile_web_app_capable' => true,
		'apple_mobile_web_app_capable' => true,
		'apple_mobile_web_app_status_bar_style' => 'default',

		// Colors
		'theme-color' => '#4F46E5',
		'ms-tile-color' => '#ffffff',
		'safari-pinned-tab-color' => '#5bbad5',

		// Default icons (will be auto-populated after generation)
		'icons' => [
			'favicon' => '/favicon.ico',
			'manifest' => '/manifest.json',
			'theme-color' => '#4F46E5',
			'ms-tile-color' => '#ffffff'
		],

		// Generation settings - GD with WebP only
		'generation' => [
			'method' => 'gd',
			'quality' => 90, // WebP quality (1-100)
			'min_source_size' => 512, // Minimum acceptable source size
			'recommended_source_size' => 512, // Recommended source size
			'auto_generate' => true, // Auto-generate missing icons
			'overwrite_existing' => false, // Don't overwrite existing files
			'formats' => ['webp'] // Only WebP output
		],

		// Web App Manifest settings
		'manifest' => [
			'name' => $title,
			'short_name' => 'VI',
			'description' => $description,
			'start_url' => '/home',
			'display' => 'standalone',
			'orientation' => 'portrait',
			'theme_color' => '#4F46E5',
			'background_color' => '#ffffff',
			'lang' => 'en-US',
			'dir' => 'ltr'
		]
	]
];