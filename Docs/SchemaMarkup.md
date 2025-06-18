# SchemaMarkup Class

Manages Schema.org structured data with JSON-LD output.

## Constructor

```php
public function __construct(array $config = [])
```

## Core Methods

### create()
Creates a generic schema of any type.

```php
public function create(string $type, array $properties = []): array
```

### add()
Adds a schema to the collection.

```php
public function add(array $schema): self
```

### render()
Outputs all schemas as JSON-LD script tags.

```php
public function render(): string
```

### validate()
Validates schema structure.

```php
public function validate(array $schema): array
```

### clear()
Removes all schemas from collection.

```php
public function clear(): self
```

## Schema Type Methods

### organization()
```php
public function organization(array $data): array
```

#### Example
```php
$orgSchema = $schema->organization([
    'name' => 'Acme Corporation',
    'url' => 'https://acme.com',
    'logo' => 'https://acme.com/logo.png',
    'description' => 'Leading provider of innovative solutions',
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
$schema->add($orgSchema);
```

### person()
```php
public function person(array $data): array
```

#### Example
```php
$personSchema = $schema->person([
    'name' => 'Jane Doe',
    'url' => 'https://janedoe.com',
    'image' => 'https://janedoe.com/photo.jpg',
    'jobTitle' => 'CEO',
    'telephone' => '+1-555-555-5555',
    'email' => 'jane@example.com'
]);
```

### product()
```php
public function product(array $data): array
```

#### Example with Review
```php
$productSchema = $schema->product([
    'name' => 'Smartphone X',
    'image' => 'https://shop.com/phone.jpg',
    'description' => 'Latest flagship smartphone',
    'brand' => 'TechBrand',
    'model' => 'X-2024',
    'price' => '999.99',
    'currency' => 'USD',
    'review' => [
        'reviewBody' => 'Amazing phone with great features!',
        'ratingValue' => '5',
        'author' => 'John Customer'
    ]
]);
$schema->add($productSchema);
```

### article()
```php
public function article(array $data): array
```

#### Example
```php
$articleSchema = $schema->article([
    'headline' => 'Breaking: Major Tech Announcement',
    'description' => 'Tech giant announces revolutionary new product',
    'image' => 'https://news.com/article-image.jpg',
    'datePublished' => '2024-01-20T10:00:00Z',
    'dateModified' => '2024-01-20T14:30:00Z',
    'author' => ['name' => 'Tech Reporter', 'url' => 'https://news.com/authors/tech'],
    'publisher' => ['name' => 'Tech News', 'logo' => 'https://news.com/logo.png']
]);
```

### event()
```php
public function event(array $data): array
```

#### Example
```php
$eventSchema = $schema->event([
    'name' => 'Web Development Conference 2024',
    'description' => 'Annual conference for web developers',
    'startDate' => '2024-06-15T09:00:00Z',
    'endDate' => '2024-06-17T18:00:00Z',
    'location' => [
        'name' => 'Convention Center',
        'address' => [
            'streetAddress' => '456 Event Plaza',
            'addressLocality' => 'San Francisco',
            'addressRegion' => 'CA',
            'postalCode' => '94105',
            'addressCountry' => 'US'
        ]
    ],
    'organizer' => 'Dev Events Inc.',
    'performer' => 'Jane Speaker'
]);
```

### recipe()
```php
public function recipe(array $data): array
```

#### Example
```php
$recipeSchema = $schema->recipe([
    'name' => 'Chocolate Chip Cookies',
    'image' => 'https://recipes.com/cookies.jpg',
    'description' => 'Classic homemade chocolate chip cookies',
    'recipeYield' => '24 cookies',
    'totalTime' => 'PT30M',
    'cookTime' => 'PT12M',
    'prepTime' => 'PT18M',
    'ingredients' => [
        '2 cups flour',
        '1 cup butter',
        '2 cups chocolate chips',
        '1 cup sugar'
    ],
    'instructions' => [
        'Preheat oven to 375°F',
        'Mix dry ingredients',
        'Cream butter and sugar',
        'Combine all ingredients',
        'Bake for 10-12 minutes'
    ],
    'nutrition' => ['calories' => '150 per cookie']
]);
```

### localBusiness()
```php
public function localBusiness(array $data): array
```

#### Example
```php
$businessSchema = $schema->localBusiness([
    'name' => 'Pizza Palace',
    'image' => 'https://pizzapalace.com/storefront.jpg',
    'description' => 'Family-owned pizzeria since 1985',
    'telephone' => '+1-555-PIZZA-00',
    'priceRange' => '$$',
    'address' => [
        'streetAddress' => '789 Main St',
        'addressLocality' => 'Brooklyn',
        'addressRegion' => 'NY',
        'postalCode' => '11201',
        'addressCountry' => 'US'
    ],
    'openingHours' => [
        'Mo-Fr 11:00-22:00',
        'Sa-Su 12:00-23:00'
    ]
]);
```

### faqPage()
```php
public function faqPage(array $questions): array
```

#### Example
```php
$faqSchema = $schema->faqPage([
    [
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept all major credit cards, PayPal, and bank transfers.'
    ],
    [
        'question' => 'Do you ship internationally?',
        'answer' => 'Yes, we ship to over 50 countries worldwide.'
    ],
    [
        'question' => 'What is your return policy?',
        'answer' => '30-day money-back guarantee on all products.'
    ]
]);
```

### Other Schema Types

- **video()** - VideoObject schema
- **book()** - Book schema
- **course()** - Course/educational content
- **softwareApplication()** - Software/app schema
- **review()** - Standalone review schema

## Complex Example: E-commerce Page

```php
$schema = new SchemaMarkup();

// Organization
$orgSchema = $schema->organization([
    'name' => 'Electronics Store',
    'url' => 'https://electronics.com',
    'logo' => 'https://electronics.com/logo.png'
]);
$schema->add($orgSchema);

// Product with AggregateRating
$productSchema = $schema->create('Product', [
    'name' => 'Laptop Pro X',
    'image' => 'https://electronics.com/laptop.jpg',
    'description' => 'High-performance laptop for professionals',
    'brand' => ['@type' => 'Brand', 'name' => 'TechBrand'],
    'offers' => [
        '@type' => 'Offer',
        'price' => '1499.99',
        'priceCurrency' => 'USD',
        'availability' => 'https://schema.org/InStock',
        'seller' => $orgSchema
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.5',
        'reviewCount' => '89'
    ]
]);
$schema->add($productSchema);

// BreadcrumbList
$breadcrumbSchema = $schema->create('BreadcrumbList', [
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => 'https://electronics.com'
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Laptops',
            'item' => 'https://electronics.com/laptops'
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => 'Laptop Pro X'
        ]
    ]
]);
$schema->add($breadcrumbSchema);

echo $schema->render();
```

## Output Example

```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Electronics Store",
    "url": "https://electronics.com",
    "logo": "https://electronics.com/logo.png"
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "Laptop Pro X",
    ...
}
</script>
```

## Best Practices

1. Use appropriate schema types for content
2. Include required properties for each type
3. Use ISO 8601 format for dates
4. Use full URLs for images and links
5. Validate with Google's Rich Results Test
6. Keep schemas focused and relevant

## Testing Tools

- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema.org Validator](https://validator.schema.org/)
- [Google Search Console](https://search.google.com/search-console)