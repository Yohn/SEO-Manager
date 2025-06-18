# Examples

- [Favicon Usage](../examples/favicon-usage.php)
- [SEO Usage](../examples/seo-usage.php)

Common SEO implementation patterns.

## Blog System

```php
class BlogController {
    private SEOManager $seo;

    public function __construct(Config $config) {
        $this->seo = new SEOManager($config);
    }

    public function showPost(int $postId): void {
        $post = $this->getPost($postId);

        $this->seo->setCommon([
            'title' => $post->title . ' | Tech Blog',
            'description' => $post->excerpt,
            'image' => $post->featured_image,
            'url' => 'https://blog.example.com/posts/' . $post->slug
        ]);

        $this->seo->meta()
            ->setKeywords($post->tags)
            ->setCustom('article:published_time', $post->published_at);

        $this->seo->openGraph()
            ->setType('article')
            ->setArticle([
                'author' => $post->author->name,
                'published_time' => $post->published_at,
                'modified_time' => $post->updated_at,
                'section' => $post->category->name,
                'tag' => $post->tags
            ]);

        $this->seo->twitter()
            ->setCreator('@' . $post->author->twitter_handle);

        $articleSchema = $this->seo->schema()->article([
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->featured_image,
            'datePublished' => $post->published_at,
            'dateModified' => $post->updated_at,
            'author' => [
                'name' => $post->author->name,
                'url' => 'https://blog.example.com/authors/' . $post->author->slug
            ],
            'publisher' => [
                'name' => 'Tech Blog',
                'logo' => 'https://blog.example.com/logo.png'
            ]
        ]);
        $this->seo->schema()->add($articleSchema);

        $this->render('post/show', [
            'post' => $post,
            'seo' => $this->seo->render()
        ]);
    }
}
```

## E-commerce Product Page

```php
class ProductController {
    private SEOManager $seo;

    public function show(string $productSlug): void {
        $product = Product::findBySlug($productSlug);
        $reviews = $product->getReviews();

        // Basic SEO setup
        $this->seo->setCommon([
            'title' => sprintf('%s - %s | Shop', $product->name, $product->brand),
            'description' => $product->short_description,
            'image' => $product->main_image,
            'url' => $product->url
        ]);

        // E-commerce specific meta
        $this->seo->meta()
            ->setKeywords(array_merge(
                [$product->brand, $product->category->name],
                $product->tags
            ));

        // Product Open Graph
        $this->seo->openGraph()
            ->setType('product')
            ->setCustom('product:price:amount', $product->price)
            ->setCustom('product:price:currency', 'USD')
            ->setCustom('product:availability', $product->in_stock ? 'in stock' : 'out of stock');

        // Product Schema with reviews
        $productSchema = [
            'name' => $product->name,
            'image' => $product->images,
            'description' => $product->description,
            'sku' => $product->sku,
            'brand' => $product->brand,
            'price' => $product->price,
            'currency' => 'USD'
        ];

        // Add aggregate rating if reviews exist
        if ($reviews->count() > 0) {
            $productSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $reviews->avg('rating'),
                'reviewCount' => $reviews->count()
            ];
        }

        $this->seo->schema()->add(
            $this->seo->schema()->product($productSchema)
        );

        // Add individual reviews
        foreach ($reviews->take(5) as $review) {
            $reviewSchema = $this->seo->schema()->review([
                'reviewBody' => $review->comment,
                'ratingValue' => $review->rating,
                'author' => $review->author_name,
                'itemReviewed' => [
                    '@type' => 'Product',
                    'name' => $product->name
                ]
            ]);
            $this->seo->schema()->add($reviewSchema);
        }

        // Breadcrumb schema
        $breadcrumbs = $this->seo->schema()->create('BreadcrumbList', [
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $product->category->name, 'item' => $product->category->url],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name]
            ]
        ]);
        $this->seo->schema()->add($breadcrumbs);
    }
}
```

## SaaS Landing Page

```php
class LandingPageController {
    public function index(): void {
        $seo = new SEOManager();

        // A/B testing different titles
        $variant = request()->get('v', 'a');
        $titles = [
            'a' => 'Streamline Your Workflow - Project Management Software',
            'b' => 'Free Project Management Tool - Start Today'
        ];

        $seo->setCommon([
            'title' => $titles[$variant],
            'description' => 'Powerful project management software trusted by 10,000+ teams. Start free.',
            'image' => 'https://app.example.com/images/og-landing.jpg',
            'url' => 'https://app.example.com'
        ]);

        // SaaS-specific meta
        $seo->meta()
            ->setKeywords(['project management', 'team collaboration', 'task tracking', 'agile', 'scrum'])
            ->setCustom('theme-color', '#4F46E5');

        // Organization schema
        $orgSchema = $seo->schema()->organization([
            'name' => 'ProjectFlow Inc.',
            'url' => 'https://app.example.com',
            'logo' => 'https://app.example.com/logo.png',
            'description' => 'Leading project management software company'
        ]);
        $seo->schema()->add($orgSchema);

        // Software Application schema
        $appSchema = $seo->schema()->softwareApplication([
            'name' => 'ProjectFlow',
            'operatingSystem' => 'Web, iOS, Android',
            'category' => 'BusinessApplication',
            'version' => '3.2.1',
            'description' => 'Comprehensive project management platform',
            'offers' => [
                'price' => '0',
                'currency' => 'USD'
            ],
            'aggregateRating' => [
                'value' => '4.8',
                'count' => '2453'
            ]
        ]);
        $seo->schema()->add($appSchema);

        // FAQ Schema
        $faqSchema = $seo->schema()->faqPage([
            [
                'question' => 'Is ProjectFlow really free?',
                'answer' => 'Yes! Our basic plan is free forever for up to 5 users.'
            ],
            [
                'question' => 'Can I import data from other tools?',
                'answer' => 'Yes, we support imports from Trello, Asana, Jira, and more.'
            ],
            [
                'question' => 'Do you offer enterprise plans?',
                'answer' => 'Yes, we have custom enterprise plans with advanced features and support.'
            ]
        ]);
        $seo->schema()->add($faqSchema);

        echo $seo->render();
    }
}
```

## Multi-language Site

```php
class LocalizedSEO {
    private array $translations = [
        'en' => [
            'site_name' => 'Global Store',
            'default_keywords' => ['online shopping', 'international shipping']
        ],
        'es' => [
            'site_name' => 'Tienda Global',
            'default_keywords' => ['compras en línea', 'envío internacional']
        ],
        'fr' => [
            'site_name' => 'Magasin Global',
            'default_keywords' => ['achats en ligne', 'livraison internationale']
        ]
    ];

    public function setupSEO(string $locale, array $pageData): SEOManager {
        $config = new Config();
        $config::set('site_name', $this->translations[$locale]['site_name'], 'seo');
        $config::set('default_keywords', $this->translations[$locale]['default_keywords'], 'seo');

        $seo = new SEOManager($config);

        // Set common properties
        $seo->setCommon($pageData);

        // Set locale and alternates
        $alternateLocales = array_diff(['en_US', 'es_ES', 'fr_FR'], [$locale . '_' . strtoupper(substr($locale, 0, 2))]);
        $seo->openGraph()->setLocale($locale . '_' . strtoupper(substr($locale, 0, 2)), $alternateLocales);

        // Add hreflang tags
        foreach (['en', 'es', 'fr'] as $lang) {
            $seo->meta()->setCustom('alternate', 'https://example.com/' . $lang . $pageData['path']);
        }

        return $seo;
    }
}
```

## API Response with SEO Data

```php
class APIController {
    public function getPageSEO(Request $request): JsonResponse {
        $page = Page::find($request->page_id);
        $seo = new SEOManager();

        $seo->setCommon([
            'title' => $page->seo_title ?? $page->title,
            'description' => $page->seo_description ?? $page->excerpt,
            'image' => $page->featured_image,
            'url' => $page->url
        ]);

        // Return SEO data as JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'page' => $page->toArray(),
                'seo' => $seo->toArray(),
                'rendered' => [
                    'meta' => $seo->meta()->render(),
                    'opengraph' => $seo->openGraph()->render(),
                    'twitter' => $seo->twitter()->render(),
                    'schema' => $seo->schema()->render()
                ]
            ]
        ]);
    }
}
```

## Dynamic Schema Generation

```php
class SchemaGenerator {
    private SchemaMarkup $schema;

    public function generateForContent($content): array {
        $this->schema = new SchemaMarkup();
        $schemas = [];

        switch ($content->type) {
            case 'recipe':
                $schemas[] = $this->schema->recipe([
                    'name' => $content->title,
                    'image' => $content->images[0] ?? '',
                    'description' => $content->description,
                    'recipeYield' => $content->meta['servings'] ?? '',
                    'totalTime' => $this->formatDuration($content->meta['total_time']),
                    'ingredients' => $content->meta['ingredients'] ?? [],
                    'instructions' => array_map(fn($step) => $step['text'], $content->meta['steps'] ?? [])
                ]);
                break;

            case 'event':
                $schemas[] = $this->schema->event([
                    'name' => $content->title,
                    'description' => $content->description,
                    'startDate' => $content->meta['start_date'],
                    'endDate' => $content->meta['end_date'],
                    'location' => [
                        'name' => $content->meta['venue_name'],
                        'address' => $content->meta['venue_address']
                    ],
                    'organizer' => $content->meta['organizer']
                ]);
                break;

            case 'course':
                $schemas[] = $this->schema->course([
                    'name' => $content->title,
                    'description' => $content->description,
                    'provider' => $content->meta['instructor'],
                    'educationalLevel' => $content->meta['level'],
                    'courseMode' => $content->meta['format'] // online, offline, blended
                ]);
                break;
        }

        foreach ($schemas as $schema) {
            $this->schema->add($schema);
        }

        return $this->schema->toArray();
    }

    private function formatDuration(int $minutes): string {
        return sprintf('PT%dH%dM', floor($minutes / 60), $minutes % 60);
    }
}
```

## Testing and Validation

```php
class SEOValidator {
    public function validatePage(string $url): array {
        $seo = new SEOManager();
        // ... setup SEO data ...

        $report = [
            'url' => $url,
            'timestamp' => date('Y-m-d H:i:s'),
            'errors' => [],
            'warnings' => [],
            'passed' => []
        ];

        // Validate each component
        $metaErrors = $seo->meta()->validate();
        $ogErrors = $seo->openGraph()->validate();
        $twitterErrors = $seo->twitter()->validate();

        $report['errors'] = array_merge($metaErrors, $ogErrors, $twitterErrors);

        // Check for warnings
        $metaData = $seo->meta()->toArray();
        if (!isset($metaData['description'])) {
            $report['warnings'][] = 'Missing meta description';
        }
        if (strlen($metaData['description'] ?? '') < 120) {
            $report['warnings'][] = 'Meta description is shorter than recommended';
        }

        // Log successful checks
        if (empty($report['errors'])) {
            $report['passed'][] = 'All required tags present';
        }

        return $report;
    }
}
```