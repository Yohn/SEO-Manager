
<?php
	// Include autoloader and initialize
	require_once 'vendor/autoload.php';

	use Yohns\Core\Config;
	use Yohns\SEO\SEOManager;

	// Initialize
	$config = new Config(__DIR__ . '/lib/config');
	$seo = new SEOManager($config, 'vi');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
<?php
	// Set up SEO for this page
	$seo->setCommon([
		'title' => 'Visual Impressions',
		'description' => 'Discover innovative solutions and services that transform your business.',
		'image' => 'https://visualimpressions.net/styles/imgs/VI_Logo_Only.png',
		'url' => 'https://visualimpressions.net'
	]);

	// Add additional meta tags
	$seo->meta()
		->setKeywords(['business solutions', 'innovation', 'services'])
		->setFavicon('/favicon.ico');

	// Configure Open Graph
	$seo->openGraph()
		->setSiteName('Amazing Website')
		->setLocale('en_US'); //, ['es_ES', 'fr_FR']);

	// Configure Twitter
	$seo->twitter()
		->setCreator('@visualimpressions');

	// Add Organization schema
	$orgSchema = $seo->schema()->organization([
		'name' => 'Amazing Company Inc.',
		'url' => 'https://visualimpressions.net',
		'logo' => 'https://visualimpressions.net/styles/imgs/VI_Logo_Only.png',
		'description' => 'Leading provider of innovative business solutions.',
		'address' => [
			'streetAddress' => '606 E Hebron St',
			'addressLocality' => 'Charlotte',
			'addressRegion' => 'NC',
			'postalCode' => '27203',
			'addressCountry' => 'US'
		],
		'telephone' => '+1-800-500-0191',
		'email' => 'info@visualimpressions.net'
	]);
	$seo->schema()->add($orgSchema);

	// Add WebPage schema
	$webPageSchema = $seo->schema()->create('WebPage', [
		'name' => 'Welcome to Our Amazing Website',
		'description' => 'Discover innovative solutions and services that transform your business.',
		'url' => 'https://visualimpressions.net',
		'publisher' => $orgSchema
	]);
	$seo->schema()->add($webPageSchema);

	// Render all SEO tags
	echo $seo->render();
	?>

	<!-- Additional styles -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yohns/picocss@2.2.10/css/pico.min.css">
	<style>
		.hero {
			text-align: center;
			padding: 4rem 0;
		}
		.features {
			margin: 4rem 0;
		}
		.feature-card {
			padding: 2rem;
			margin-bottom: 2rem;
		}
		pre {
			background: #f4f4f4;
			padding: 1rem;
			overflow-x: auto;
		}
	</style>
</head>
<body>
	<nav class="container-fluid">
		<ul>
			<li><strong>Amazing Website</strong></li>
		</ul>
		<ul>
			<li><a href="#features">Features</a></li>
			<li><a href="#example">Example</a></li>
			<li><a href="#contact">Contact</a></li>
		</ul>
	</nav>

	<main class="container">
		<section class="hero">
			<h1>SEO Manager for PHP 8.2+</h1>
			<p>A comprehensive solution for managing all your SEO needs</p>
		</section>

		<section id="features" class="features">
			<h2>Features</h2>
			<div class="grid">
				<article class="feature-card">
					<h3>Meta Tags</h3>
					<p>Manage title, description, keywords, canonical URLs, and more with built-in validation.</p>
				</article>
				<article class="feature-card">
					<h3>Open Graph</h3>
					<p>Full support for Open Graph protocol including articles, products, videos, and events.</p>
				</article>
				<article class="feature-card">
					<h3>Twitter Cards</h3>
					<p>Implement all Twitter Card types with proper validation and character limits.</p>
				</article>
				<article class="feature-card">
					<h3>Schema Markup</h3>
					<p>Comprehensive Schema.org support with helpers for common types and JSON-LD output.</p>
				</article>
			</div>
		</section>

		<section id="example">
			<h2>Example Output</h2>
			<p>Here's what the SEO Manager generates for this page:</p>
			<pre><code><?php echo htmlspecialchars($seo->render()); ?></code></pre>
		</section>

		<section id="contact">
			<h2>Get Started</h2>
			<p>Ready to improve your website's SEO? Install the SEO Manager today!</p>
			<pre><code>composer require yohns/seo-manager</code></pre>
		</section>
	</main>

	<footer class="container">
		<small>© 2024 Amazing Company Inc. All rights reserved.</small>
	</footer>
</body>
</html>