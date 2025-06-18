<?php

namespace Yohns\SEO;

use Yohns\Core\Config;

/**
 * Main SEO Manager class that orchestrates all SEO components
 */
class SEOManager {
	private MetaTags     $metaTags;
	private OpenGraph    $openGraph;
	private TwitterCards $twitterCards;
	private SchemaMarkup $schemaMarkup;
	private Favicons     $favicons;
	private array        $config       = [];

	public function __construct(?Config $config = null) {
		if ($config) {
			$this->config = $config::getAll('seo') ?? [];
		}

		$this->metaTags = new MetaTags($this->config['meta'] ?? []);
		$this->openGraph = new OpenGraph($this->config['opengraph'] ?? []);
		$this->twitterCards = new TwitterCards($this->config['twitter'] ?? []);
		$this->schemaMarkup = new SchemaMarkup($this->config['schema'] ?? []);
		$this->favicons = new Favicons($this->config['favicons'] ?? []);
	}

	/**
	 * Get MetaTags instance
	 */
	public function meta(): MetaTags {
		return $this->metaTags;
	}

	/**
	 * Get OpenGraph instance
	 */
	public function openGraph(): OpenGraph {
		return $this->openGraph;
	}

	/**
	 * Get TwitterCards instance
	 */
	public function twitter(): TwitterCards {
		return $this->twitterCards;
	}

	/**
	 * Get SchemaMarkup instance
	 */
	public function schema(): SchemaMarkup {
		return $this->schemaMarkup;
	}

	/**
	 * Get Favicons instance
	 */
	public function favicons(): Favicons {
		return $this->favicons;
	}

	/**
	 * Render all SEO tags
	 */
	public function render(): string {
		$output = [];

		$output[] = $this->favicons->render();
		$output[] = $this->metaTags->render();
		$output[] = $this->openGraph->render();
		$output[] = $this->twitterCards->render();
		$output[] = $this->schemaMarkup->render();

		return implode("\n", array_filter($output));
	}

	/**
	 * Get all SEO data as array
	 */
	public function toArray(): array {
		return [
			'meta'      => $this->metaTags->toArray(),
			'opengraph' => $this->openGraph->toArray(),
			'twitter'   => $this->twitterCards->toArray(),
			'schema'    => $this->schemaMarkup->toArray(),
			'favicons'  => $this->favicons->toArray()
		];
	}

	/**
	 * Set common properties across all components
	 */
	public function setCommon(array $data): self {
		if (isset($data['title'])) {
			$this->metaTags->setTitle($data['title']);
			$this->openGraph->setTitle($data['title']);
			$this->twitterCards->setTitle($data['title']);
		}

		if (isset($data['description'])) {
			$this->metaTags->setDescription($data['description']);
			$this->openGraph->setDescription($data['description']);
			$this->twitterCards->setDescription($data['description']);
		}

		if (isset($data['image'])) {
			$this->openGraph->setImage($data['image']);
			$this->twitterCards->setImage($data['image']);
		}

		if (isset($data['url'])) {
			$this->metaTags->setCanonical($data['url']);
			$this->openGraph->setUrl($data['url']);
		}

		return $this;
	}
}