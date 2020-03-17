<?php

namespace WPML\ST\TranslationFile;

class StringEntity {

	/** @var string $original */
	private $original;

	/** @var array $translations */
	private $translations = array();

	/** @var null|string $context */
	private $context;

	/** @var string|null */
	private $original_plural;

	/**
	 * @param string      $original
	 * @param array       $translations
	 * @param null|string $context
	 * @param null|string $original_plural
	 */
	public function __construct( $original, array $translations, $context = null, $original_plural = null ) {
		$this->original     = $original;
		$this->translations = $translations;
		$this->context      = $context ? $context : null;
		$this->original_plural = $original_plural;
	}

	/** @return string */
	public function get_original() {
		return $this->original;
	}

	/** @return array */
	public function get_translations() {
		return $this->translations;
	}

	/** @return null|string */
	public function get_context() {
		return $this->context;
	}

	/**
	 * @return string|null
	 */
	public function get_original_plural() {
		return $this->original_plural;
	}
}
