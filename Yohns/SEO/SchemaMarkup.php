<?php

namespace Yohns\SEO;

/**
 * SchemaMarkup class for managing Schema.org structured data
 */
class SchemaMarkup {
	private array $schemas = [];
	private array $config = [];
	private string $context = 'https://schema.org';

	public function __construct(array $config = []) {
		$this->config = $config;
	}

	/**
	 * Create a generic schema
	 */
	public function create(string $type, array $properties = []): array {
		$schema = [
			'@context' => $this->context,
			'@type' => $type
		];

		return array_merge($schema, $properties);
	}

	/**
	 * Add a schema to the collection
	 */
	public function add(array $schema): self {
		$this->schemas[] = $schema;
		return $this;
	}

	/**
	 * Create Organization schema
	 */
	public function organization(array $data): array {
		$schema = $this->create('Organization', [
			'name' => $data['name'] ?? '',
			'url' => $data['url'] ?? '',
			'logo' => $data['logo'] ?? '',
			'description' => $data['description'] ?? ''
		]);

		if (isset($data['address'])) {
			$schema['address'] = $this->postalAddress($data['address']);
		}

		if (isset($data['telephone'])) {
			$schema['telephone'] = $data['telephone'];
		}

		if (isset($data['email'])) {
			$schema['email'] = $data['email'];
		}

		return array_filter($schema);
	}

	/**
	 * Create Person schema
	 */
	public function person(array $data): array {
		$schema = $this->create('Person', [
			'name' => $data['name'] ?? '',
			'url' => $data['url'] ?? '',
			'image' => $data['image'] ?? '',
			'description' => $data['description'] ?? '',
			'jobTitle' => $data['jobTitle'] ?? ''
		]);

		if (isset($data['birthDate'])) {
			$schema['birthDate'] = $data['birthDate'];
		}

		if (isset($data['telephone'])) {
			$schema['telephone'] = $data['telephone'];
		}

		if (isset($data['email'])) {
			$schema['email'] = $data['email'];
		}

		return array_filter($schema);
	}

	/**
	 * Create Product schema
	 */
	public function product(array $data): array {
		$schema = $this->create('Product', [
			'name' => $data['name'] ?? '',
			'image' => $data['image'] ?? '',
			'description' => $data['description'] ?? ''
		]);

		if (isset($data['brand'])) {
			$schema['brand'] = [
				'@type' => 'Brand',
				'name' => $data['brand']
			];
		}

		if (isset($data['model'])) {
			$schema['model'] = $data['model'];
		}

		if (isset($data['price'])) {
			$schema['offers'] = [
				'@type' => 'Offer',
				'price' => $data['price'],
				'priceCurrency' => $data['currency'] ?? 'USD'
			];
		}

		if (isset($data['review'])) {
			$schema['review'] = $this->review($data['review']);
		}

		return array_filter($schema);
	}

	/**
	 * Create Article schema
	 */
	public function article(array $data): array {
		$schema = $this->create('Article', [
			'headline' => $data['headline'] ?? $data['name'] ?? '',
			'description' => $data['description'] ?? '',
			'image' => $data['image'] ?? '',
			'datePublished' => $data['datePublished'] ?? '',
			'dateModified' => $data['dateModified'] ?? $data['datePublished'] ?? ''
		]);

		if (isset($data['author'])) {
			$schema['author'] = is_array($data['author'])
				? $this->person($data['author'])
				: ['@type' => 'Person', 'name' => $data['author']];
		}

		if (isset($data['publisher'])) {
			$schema['publisher'] = is_array($data['publisher'])
				? $this->organization($data['publisher'])
				: ['@type' => 'Organization', 'name' => $data['publisher']];
		}

		return array_filter($schema);
	}

	/**
	 * Create Event schema
	 */
	public function event(array $data): array {
		$schema = $this->create('Event', [
			'name' => $data['name'] ?? '',
			'description' => $data['description'] ?? '',
			'startDate' => $data['startDate'] ?? '',
			'endDate' => $data['endDate'] ?? ''
		]);

		if (isset($data['location'])) {
			$schema['location'] = $this->place($data['location']);
		}

		if (isset($data['organizer'])) {
			$schema['organizer'] = is_array($data['organizer'])
				? $this->organization($data['organizer'])
				: ['@type' => 'Organization', 'name' => $data['organizer']];
		}

		if (isset($data['performer'])) {
			$schema['performer'] = is_array($data['performer'])
				? $this->person($data['performer'])
				: ['@type' => 'Person', 'name' => $data['performer']];
		}

		return array_filter($schema);
	}

	/**
	 * Create Recipe schema
	 */
	public function recipe(array $data): array {
		$schema = $this->create('Recipe', [
			'name' => $data['name'] ?? '',
			'image' => $data['image'] ?? '',
			'description' => $data['description'] ?? '',
			'recipeYield' => $data['recipeYield'] ?? '',
			'totalTime' => $data['totalTime'] ?? '',
			'cookTime' => $data['cookTime'] ?? '',
			'prepTime' => $data['prepTime'] ?? ''
		]);

		if (isset($data['ingredients'])) {
			$schema['recipeIngredient'] = $data['ingredients'];
		}

		if (isset($data['instructions'])) {
			$schema['recipeInstructions'] = array_map(function($instruction) {
				return [
					'@type' => 'HowToStep',
					'text' => $instruction
				];
			}, $data['instructions']);
		}

		if (isset($data['nutrition'])) {
			$schema['nutrition'] = [
				'@type' => 'NutritionInformation',
				'calories' => $data['nutrition']['calories'] ?? ''
			];
		}

		return array_filter($schema);
	}

	/**
	 * Create Review schema
	 */
	public function review(array $data): array {
		$schema = $this->create('Review', [
			'reviewBody' => $data['reviewBody'] ?? '',
			'reviewRating' => [
				'@type' => 'Rating',
				'ratingValue' => $data['ratingValue'] ?? '',
				'bestRating' => $data['bestRating'] ?? '5',
				'worstRating' => $data['worstRating'] ?? '1'
			]
		]);

		if (isset($data['author'])) {
			$schema['author'] = is_array($data['author'])
				? $this->person($data['author'])
				: ['@type' => 'Person', 'name' => $data['author']];
		}

		if (isset($data['itemReviewed'])) {
			$schema['itemReviewed'] = $data['itemReviewed'];
		}

		return array_filter($schema);
	}

	/**
	 * Create VideoObject schema
	 */
	public function video(array $data): array {
		$schema = $this->create('VideoObject', [
			'name' => $data['name'] ?? '',
			'description' => $data['description'] ?? '',
			'thumbnailUrl' => $data['thumbnailUrl'] ?? '',
			'uploadDate' => $data['uploadDate'] ?? '',
			'duration' => $data['duration'] ?? '',
			'contentUrl' => $data['contentUrl'] ?? '',
			'embedUrl' => $data['embedUrl'] ?? ''
		]);

		if (isset($data['publisher'])) {
			$schema['publisher'] = is_array($data['publisher'])
				? $this->organization($data['publisher'])
				: ['@type' => 'Organization', 'name' => $data['publisher']];
		}

		return array_filter($schema);
	}

	/**
	 * Create Book schema
	 */
	public function book(array $data): array {
		$schema = $this->create('Book', [
			'name' => $data['name'] ?? '',
			'author' => $data['author'] ?? '',
			'isbn' => $data['isbn'] ?? '',
			'description' => $data['description'] ?? '',
			'image' => $data['image'] ?? ''
		]);

		if (isset($data['author']) && is_array($data['author'])) {
			$schema['author'] = $this->person($data['author']);
		}

		if (isset($data['publisher'])) {
			$schema['publisher'] = is_array($data['publisher'])
				? $this->organization($data['publisher'])
				: ['@type' => 'Organization', 'name' => $data['publisher']];
		}

		if (isset($data['datePublished'])) {
			$schema['datePublished'] = $data['datePublished'];
		}

		return array_filter($schema);
	}

	/**
	 * Create Course schema
	 */
	public function course(array $data): array {
		$schema = $this->create('Course', [
			'name' => $data['name'] ?? '',
			'description' => $data['description'] ?? '',
			'provider' => $data['provider'] ?? ''
		]);

		if (isset($data['provider']) && is_array($data['provider'])) {
			$schema['provider'] = $this->organization($data['provider']);
		}

		if (isset($data['educationalLevel'])) {
			$schema['educationalLevel'] = $data['educationalLevel'];
		}

		if (isset($data['courseMode'])) {
			$schema['courseMode'] = $data['courseMode'];
		}

		return array_filter($schema);
	}

	/**
	 * Create LocalBusiness schema
	 */
	public function localBusiness(array $data): array {
		$schema = $this->create('LocalBusiness', [
			'name' => $data['name'] ?? '',
			'image' => $data['image'] ?? '',
			'description' => $data['description'] ?? '',
			'telephone' => $data['telephone'] ?? ''
		]);

		if (isset($data['address'])) {
			$schema['address'] = $this->postalAddress($data['address']);
		}

		if (isset($data['openingHours'])) {
			$schema['openingHoursSpecification'] = $data['openingHours'];
		}

		if (isset($data['priceRange'])) {
			$schema['priceRange'] = $data['priceRange'];
		}

		return array_filter($schema);
	}

	/**
	 * Create FAQPage schema
	 */
	public function faqPage(array $questions): array {
		$schema = $this->create('FAQPage');

		$schema['mainEntity'] = array_map(function($qa) {
			return [
				'@type' => 'Question',
				'name' => $qa['question'],
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => $qa['answer']
				]
			];
		}, $questions);

		return $schema;
	}

	/**
	 * Create SoftwareApplication schema
	 */
	public function softwareApplication(array $data): array {
		$schema = $this->create('SoftwareApplication', [
			'name' => $data['name'] ?? '',
			'operatingSystem' => $data['operatingSystem'] ?? '',
			'applicationCategory' => $data['category'] ?? '',
			'softwareVersion' => $data['version'] ?? '',
			'description' => $data['description'] ?? ''
		]);

		if (isset($data['offers'])) {
			$schema['offers'] = [
				'@type' => 'Offer',
				'price' => $data['offers']['price'] ?? '0',
				'priceCurrency' => $data['offers']['currency'] ?? 'USD'
			];
		}

		if (isset($data['aggregateRating'])) {
			$schema['aggregateRating'] = [
				'@type' => 'AggregateRating',
				'ratingValue' => $data['aggregateRating']['value'] ?? '',
				'ratingCount' => $data['aggregateRating']['count'] ?? ''
			];
		}

		return array_filter($schema);
	}

	/**
	 * Create PostalAddress schema
	 */
	private function postalAddress(array $data): array {
		return array_filter([
			'@type' => 'PostalAddress',
			'streetAddress' => $data['streetAddress'] ?? '',
			'addressLocality' => $data['addressLocality'] ?? '',
			'addressRegion' => $data['addressRegion'] ?? '',
			'postalCode' => $data['postalCode'] ?? '',
			'addressCountry' => $data['addressCountry'] ?? ''
		]);
	}

	/**
	 * Create Place schema
	 */
	private function place(array $data): array {
		$place = [
			'@type' => 'Place',
			'name' => $data['name'] ?? ''
		];

		if (isset($data['address'])) {
			$place['address'] = $this->postalAddress($data['address']);
		}

		return array_filter($place);
	}

	/**
	 * Validate schema
	 */
	public function validate(array $schema): array {
		$errors = [];

		if (!isset($schema['@context'])) {
			$errors[] = "Schema missing @context";
		}

		if (!isset($schema['@type'])) {
			$errors[] = "Schema missing @type";
		}

		// Additional validation based on type
		switch ($schema['@type'] ?? '') {
			case 'Article':
				if (!isset($schema['headline'])) {
					$errors[] = "Article schema missing headline";
				}
				break;
			case 'Product':
				if (!isset($schema['name'])) {
					$errors[] = "Product schema missing name";
				}
				break;
			case 'Event':
				if (!isset($schema['name']) || !isset($schema['startDate'])) {
					$errors[] = "Event schema missing required fields (name, startDate)";
				}
				break;
		}

		return $errors;
	}

	/**
	 * Render schemas as JSON-LD
	 */
	public function render(): string {
		if (empty($this->schemas)) {
			return '';
		}

		$output = [];

		foreach ($this->schemas as $schema) {
			$json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
			$output[] = sprintf('<script type="application/ld+json">%s</script>', $json);
		}

		return implode("\n", $output);
	}

	/**
	 * Get all schemas as array
	 */
	public function toArray(): array {
		return $this->schemas;
	}

	/**
	 * Clear all schemas
	 */
	public function clear(): self {
		$this->schemas = [];
		return $this;
	}
	/**
	 * Set context for schemas
	 */
	public function setContext(string $context): self {
		$this->context = $context;
		return $this;
	}
	/**
	 * Get current context
	 */
	public function getContext(): string {
		return $this->context;
	}
	/**
	 * Get all schemas
	 */
	public function getSchemas(): array {
		return $this->schemas;
	}
	/**
	 * Get config
	 */
	public function getConfig(): array {
		return $this->config;
	}
}