<?php

namespace Yohns\SEO;

/**
 * MetaTags class for managing HTML meta tags
 */
class MetaTags {
	private array $tags   = [];
	private array $config = [];

	// Character limits
	private const TITLE_MAX_LENGTH = 60;
	private const DESCRIPTION_MIN_LENGTH = 155;
	private const DESCRIPTION_MAX_LENGTH = 160;
	private const KEYWORDS_MAX_COUNT = 10;

	public function __construct(array $config = []) {
		$this->config = $config;
		$this->setDefaults();
	}

	/**
	 * Set default values from config
	 */
	private function setDefaults(): void {
		if (isset($this->config['defaults'])) {
			foreach ($this->config['defaults'] as $key => $value) {
				$this->tags[$key] = $value;
			}
		}
	}

	/**
	 * Set title tag
	 */
	public function setTitle(string $title): self {
		$title = trim($title);
		if (strlen($title) > self::TITLE_MAX_LENGTH) {
			$title = substr($title, 0, self::TITLE_MAX_LENGTH - 3) . '...';
		}
		$this->tags['title'] = $title;
		return $this;
	}

	/**
	 * Set meta description
	 */
	public function setDescription(string $description): self {
		$description = trim($description);
		$length = strlen($description);

		if ($length > self::DESCRIPTION_MAX_LENGTH) {
			$description = substr($description, 0, self::DESCRIPTION_MAX_LENGTH - 3) . '...';
		}

		$this->tags['description'] = $description;
		return $this;
	}

	/**
	 * Set meta keywords
	 */
	public function setKeywords(array|string $keywords): self {
		if (is_string($keywords)) {
			$keywords = array_map('trim', explode(',', $keywords));
		}

		// Normalize keywords (lowercase, remove duplicates)
		$keywords = array_unique(array_map('strtolower', $keywords));

		// Limit to max count
		if (count($keywords) > self::KEYWORDS_MAX_COUNT) {
			$keywords = array_slice($keywords, 0, self::KEYWORDS_MAX_COUNT);
		}

		$this->tags['keywords'] = implode(', ', $keywords);
		return $this;
	}

	/**
	 * Set canonical URL
	 */
	public function setCanonical(string $url): self {
		$this->tags['canonical'] = $url;
		return $this;
	}

	/**
	 * Set robots meta tag
	 */
	public function setRobots(string $robots): self {
		$this->tags['robots'] = $robots;
		return $this;
	}

	/**
	 * Set viewport meta tag
	 */
	public function setViewport(string $viewport = 'width=device-width, initial-scale=1'): self {
		$this->tags['viewport'] = $viewport;
		return $this;
	}

	/**
	 * Set favicon
	 */
	public function setFavicon(string $url): self {
		$this->tags['favicon'] = $url;
		return $this;
	}

	/**
	 * Set custom meta tag
	 */
	public function setCustom(string $name, string $content): self {
		$this->tags['custom'][$name] = $content;
		return $this;
	}

	/**
	 * Validate meta tags
	 */
	public function validate(): array {
		$errors = [];

		if (isset($this->tags['title']) && strlen($this->tags['title']) > self::TITLE_MAX_LENGTH) {
			$errors[] = "Title exceeds maximum length of " . self::TITLE_MAX_LENGTH . " characters";
		}

		if (isset($this->tags['description'])) {
			$length = strlen($this->tags['description']);
			if ($length < self::DESCRIPTION_MIN_LENGTH) {
				$errors[] = "Description is below recommended minimum of " . self::DESCRIPTION_MIN_LENGTH . " characters";
			}
			if ($length > self::DESCRIPTION_MAX_LENGTH) {
				$errors[] = "Description exceeds maximum length of " . self::DESCRIPTION_MAX_LENGTH . " characters";
			}
		}

		if (isset($this->tags['keywords'])) {
			$count = count(explode(',', $this->tags['keywords']));
			if ($count > self::KEYWORDS_MAX_COUNT) {
				$errors[] = "Keywords exceed recommended maximum of " . self::KEYWORDS_MAX_COUNT;
			}
		}

		return $errors;
	}

	/**
	 * Render meta tags as HTML
	 */
	public function render(): string {
		$output = [];

		// Title tag
		if (isset($this->tags['title'])) {
			$output[] = sprintf('<title>%s</title>', htmlspecialchars($this->tags['title']));
		}

		// Description
		if (isset($this->tags['description'])) {
			$output[] = sprintf('<meta name="description" content="%s">', htmlspecialchars($this->tags['description']));
		}

		// Keywords
		if (isset($this->tags['keywords'])) {
			$output[] = sprintf('<meta name="keywords" content="%s">', htmlspecialchars($this->tags['keywords']));
		}

		// Robots
		if (isset($this->tags['robots'])) {
			$output[] = sprintf('<meta name="robots" content="%s">', htmlspecialchars($this->tags['robots']));
		}

		// Viewport
		if (isset($this->tags['viewport'])) {
			$output[] = sprintf('<meta name="viewport" content="%s">', htmlspecialchars($this->tags['viewport']));
		}

		// Canonical
		if (isset($this->tags['canonical'])) {
			$output[] = sprintf('<link rel="canonical" href="%s">', htmlspecialchars($this->tags['canonical']));
		}

		// Favicon
		if (isset($this->tags['favicon'])) {
			$output[] = sprintf('<link rel="icon" href="%s">', htmlspecialchars($this->tags['favicon']));
		}

		// Custom meta tags
		if (isset($this->tags['custom'])) {
			foreach ($this->tags['custom'] as $name => $content) {
				$output[] = sprintf('<meta name="%s" content="%s">', htmlspecialchars($name), htmlspecialchars($content));
			}
		}

		return implode("\n", $output);
	}

	/**
	 * Get meta tags as array
	 */
	public function toArray(): array {
		return $this->tags;
	}
}