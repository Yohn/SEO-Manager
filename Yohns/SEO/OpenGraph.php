<?php

namespace Yohns\SEO;

/**
 * OpenGraph class for managing Open Graph meta tags
 */
class OpenGraph {
	private array $tags   = [];
	private array $config = [];

	// Valid Open Graph types
	private const VALID_TYPES = [
		'website', 'article', 'book', 'profile', 'music.song', 'music.album',
		'music.playlist', 'music.radio_station', 'video.movie', 'video.episode',
		'video.tv_show', 'video.other'
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

		// Set default type if not set
		if (!isset($this->tags['type'])) {
			$this->tags['type'] = 'website';
		}
	}

	/**
	 * Set Open Graph title
	 */
	public function setTitle(string $title): self {
		$this->tags['title'] = trim($title);
		return $this;
	}

	/**
	 * Set Open Graph type
	 */
	public function setType(string $type): self {
		if (!in_array($type, self::VALID_TYPES)) {
			throw new \InvalidArgumentException("Invalid Open Graph type: $type");
		}
		$this->tags['type'] = $type;
		return $this;
	}

	/**
	 * Set Open Graph URL
	 */
	public function setUrl(string $url): self {
		$this->tags['url'] = $url;
		return $this;
	}

	/**
	 * Set Open Graph image
	 */
	public function setImage(string $url, ?int $width = null, ?int $height = null, ?string $alt = null): self {
		$this->tags['image'] = $url;

		if ($width !== null) {
			$this->tags['image:width'] = $width;
		}
		if ($height !== null) {
			$this->tags['image:height'] = $height;
		}
		if ($alt !== null) {
			$this->tags['image:alt'] = $alt;
		}

		return $this;
	}

	/**
	 * Set Open Graph description
	 */
	public function setDescription(string $description): self {
		$this->tags['description'] = trim($description);
		return $this;
	}

	/**
	 * Set Open Graph site name
	 */
	public function setSiteName(string $siteName): self {
		$this->tags['site_name'] = $siteName;
		return $this;
	}

	/**
	 * Set Open Graph locale
	 */
	public function setLocale(string $locale, array $alternates = []): self {
		$this->tags['locale'] = $locale;

		if (!empty($alternates)) {
			$this->tags['locale:alternate'] = $alternates;
		}

		return $this;
	}

	/**
	 * Set Open Graph video
	 */
	public function setVideo(string $url, ?int $width = null, ?int $height = null, ?string $type = null): self {
		$this->tags['video'] = $url;

		if ($width !== null) {
			$this->tags['video:width'] = $width;
		}
		if ($height !== null) {
			$this->tags['video:height'] = $height;
		}
		if ($type !== null) {
			$this->tags['video:type'] = $type;
		}

		return $this;
	}

	/**
	 * Set Open Graph audio
	 */
	public function setAudio(string $url, ?string $type = null): self {
		$this->tags['audio'] = $url;

		if ($type !== null) {
			$this->tags['audio:type'] = $type;
		}

		return $this;
	}

	/**
	 * Set article-specific properties
	 */
	public function setArticle(array $properties): self {
		$allowedProps = ['author', 'published_time', 'modified_time', 'section', 'tag'];

		foreach ($properties as $key => $value) {
			if (in_array($key, $allowedProps)) {
				$this->tags["article:$key"] = $value;
			}
		}

		return $this;
	}

	/**
	 * Set custom Open Graph property
	 */
	public function setCustom(string $property, string $content): self {
		$this->tags[$property] = $content;
		return $this;
	}

	/**
	 * Validate Open Graph tags
	 */
	public function validate(): array {
		$errors = [];
		$required = ['title', 'type', 'url', 'image'];

		foreach ($required as $tag) {
			if (!isset($this->tags[$tag])) {
				$errors[] = "Required Open Graph tag missing: og:$tag";
			}
		}

		if (isset($this->tags['type']) && !in_array($this->tags['type'], self::VALID_TYPES)) {
			$errors[] = "Invalid Open Graph type: " . $this->tags['type'];
		}

		return $errors;
	}

	/**
	 * Render Open Graph tags as HTML
	 */
	public function render(): string {
		$output = [];

		foreach ($this->tags as $property => $content) {
			if ($property === 'locale:alternate' && is_array($content)) {
				foreach ($content as $locale) {
					$output[] = sprintf('<meta property="og:%s" content="%s">',
						htmlspecialchars($property),
						htmlspecialchars($locale)
					);
				}
			} elseif ($property === 'tag' && is_array($content)) {
				foreach ($content as $tag) {
					$output[] = sprintf('<meta property="og:article:tag" content="%s">',
						htmlspecialchars($tag)
					);
				}
			} else {
				$output[] = sprintf('<meta property="og:%s" content="%s">',
					htmlspecialchars($property),
					htmlspecialchars($content)
				);
			}
		}

		return implode("\n", $output);
	}

	/**
	 * Get Open Graph tags as array
	 */
	public function toArray(): array {
		return $this->tags;
	}
}