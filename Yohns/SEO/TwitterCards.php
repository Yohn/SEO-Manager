<?php

namespace Yohns\SEO;

/**
 * TwitterCards class for managing Twitter Card meta tags
 */
class TwitterCards {
	private array $tags = [];
	private array $config = [];

	// Valid Twitter Card types
	private const VALID_CARD_TYPES = [
		'summary', 'summary_large_image', 'app', 'player'
	];

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

		// Set default card type if not set
		if (!isset($this->tags['card'])) {
			$this->tags['card'] = 'summary';
		}
	}

	/**
	 * Set Twitter Card type
	 */
	public function setCard(string $type): self {
		if (!in_array($type, self::VALID_CARD_TYPES)) {
			throw new \InvalidArgumentException("Invalid Twitter Card type: $type");
		}
		$this->tags['card'] = $type;
		return $this;
	}

	/**
	 * Set Twitter site username
	 */
	public function setSite(string $username): self {
		$this->tags['site'] = $this->normalizeUsername($username);
		return $this;
	}

	/**
	 * Set Twitter creator username
	 */
	public function setCreator(string $username): self {
		$this->tags['creator'] = $this->normalizeUsername($username);
		return $this;
	}

	/**
	 * Set Twitter Card title
	 */
	public function setTitle(string $title): self {
		// Twitter recommends max 70 characters
		if (strlen($title) > 70) {
			$title = substr($title, 0, 67) . '...';
		}
		$this->tags['title'] = trim($title);
		return $this;
	}

	/**
	 * Set Twitter Card description
	 */
	public function setDescription(string $description): self {
		// Twitter recommends max 200 characters
		if (strlen($description) > 200) {
			$description = substr($description, 0, 197) . '...';
		}
		$this->tags['description'] = trim($description);
		return $this;
	}

	/**
	 * Set Twitter Card image
	 */
	public function setImage(string $url, ?string $alt = null): self {
		$this->tags['image'] = $url;

		if ($alt !== null) {
			// Twitter recommends max 420 characters for alt text
			if (strlen($alt) > 420) {
				$alt = substr($alt, 0, 417) . '...';
			}
			$this->tags['image:alt'] = $alt;
		}

		return $this;
	}

	/**
	 * Set player card properties
	 */
	public function setPlayer(string $url, int $width, int $height, ?string $stream = null): self {
		$this->tags['player'] = $url;
		$this->tags['player:width'] = $width;
		$this->tags['player:height'] = $height;

		if ($stream !== null) {
			$this->tags['player:stream'] = $stream;
		}

		return $this;
	}

	/**
	 * Set app card properties
	 */
	public function setApp(array $properties): self {
		$platforms = ['iphone', 'ipad', 'googleplay'];
		$appProps = ['id', 'name', 'url'];

		foreach ($properties as $platform => $props) {
			if (in_array($platform, $platforms)) {
				foreach ($props as $key => $value) {
					if (in_array($key, $appProps)) {
						$this->tags["app:{$key}:{$platform}"] = $value;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Set custom Twitter Card property
	 */
	public function setCustom(string $property, string $content): self {
		$this->tags[$property] = $content;
		return $this;
	}

	/**
	 * Normalize Twitter username
	 */
	private function normalizeUsername(string $username): string {
		// Ensure username starts with @
		if (!str_starts_with($username, '@')) {
			$username = '@' . $username;
		}
		return $username;
	}

	/**
	 * Validate Twitter Card tags
	 */
	public function validate(): array {
		$errors = [];

		// Check required tags based on card type
		$required = ['card'];

		switch ($this->tags['card'] ?? '') {
			case 'summary':
			case 'summary_large_image':
				$required = array_merge($required, ['title', 'description']);
				break;
			case 'app':
				// App cards need at least one app ID
				$hasAppId = false;
				foreach ($this->tags as $key => $value) {
					if (str_contains($key, 'app:id:')) {
						$hasAppId = true;
						break;
					}
				}
				if (!$hasAppId) {
					$errors[] = "App card requires at least one app ID";
				}
				break;
			case 'player':
				$required = array_merge($required, ['title', 'player', 'player:width', 'player:height']);
				break;
		}

		foreach ($required as $tag) {
			if (!isset($this->tags[$tag])) {
				$errors[] = "Required Twitter Card tag missing: twitter:$tag";
			}
		}

		// Validate card type
		if (isset($this->tags['card']) && !in_array($this->tags['card'], self::VALID_CARD_TYPES)) {
			$errors[] = "Invalid Twitter Card type: " . $this->tags['card'];
		}

		// Validate image requirements
		if (isset($this->tags['card']) && $this->tags['card'] === 'summary_large_image' && !isset($this->tags['image'])) {
			$errors[] = "Summary large image card requires an image";
		}

		return $errors;
	}

	/**
	 * Render Twitter Card tags as HTML
	 */
	public function render(): string {
		$output = [];

		foreach ($this->tags as $property => $content) {
			$output[] = sprintf('<meta name="twitter:%s" content="%s">',
				htmlspecialchars($property),
				htmlspecialchars($content)
			);
		}

		return implode("\n", $output);
	}

	/**
	 * Get Twitter Card tags as array
	 */
	public function toArray(): array {
		return $this->tags;
	}
}
