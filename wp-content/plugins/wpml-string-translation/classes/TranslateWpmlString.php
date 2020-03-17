<?php

namespace WPML\ST;

use WPML\ST\Gettext\Settings as GettextSettings;
use WPML\ST\MO\Hooks\LanguageSwitch;
use WPML\ST\MO\File\Manager;
use WPML\ST\StringsFilter\Provider;
use WPML_Locale;

class TranslateWpmlString {

	/** @var array $loadedDomains */
	private static $loadedDomains = [];

	/** @var Provider $filterProvider */
	private $filterProvider;

	/** @var LanguageSwitch $languageSwitch */
	private $languageSwitch;

	/** @var WPML_Locale $locale */
	private $locale;

	/** @var GettextSettings $gettextSettings */
	private $gettextSettings;

	/** @var Manager $fileManager */
	private $fileManager;

	/** @var bool $isAutoRegisterDisabled */
	private $isAutoRegisterDisabled;

	/** @var bool $lock */
	private $lock = false;

	public function __construct(
		Provider $filterProvider,
		LanguageSwitch $languageSwitch,
		WPML_Locale $locale,
		GettextSettings $gettextSettings,
		Manager $fileManager
	) {
		$this->filterProvider  = $filterProvider;
		$this->languageSwitch  = $languageSwitch;
		$this->locale          = $locale;
		$this->gettextSettings = $gettextSettings;
		$this->fileManager     = $fileManager;
	}

	public function init() {
		$this->languageSwitch->initCurrentLocale();
		$this->isAutoRegisterDisabled = ! $this->gettextSettings->isAutoRegistrationEnabled();
	}

	/**
	 * @param string|array $wpmlContext
	 * @param string       $name
	 * @param bool         $value
	 * @param bool         $allowEmptyValue
	 * @param null|bool    $hasTranslation
	 * @param null|string  $targetLang
	 *
	 * @return bool|string
	 */
	public function translate( $wpmlContext, $name, $value = false, $allowEmptyValue = false, &$hasTranslation = null, $targetLang = null ) {
		if ( $this->lock ) {
			return $value;
		}

		$this->lock = true;

		if ( wpml_st_is_requested_blog() ) {

			list ( $domain, $gettextContext ) = wpml_st_extract_context_parameters( $wpmlContext );

			if ( $this->isAutoRegisterDisabled && self::canTranslateWithMO( $value, $name ) ) {
				$customContext = self::getCustomContext( $name, $gettextContext );
				$new_value = $this->withMOLocale( $targetLang, function( $locale ) use ( $value, $customContext, $domain ) {
					$this->loadTextDomain( $domain, $locale );
					return _x( $value, $customContext, $domain );
				});
				$hasTranslation = $new_value !== $value;
				$value          = $new_value;

			} else {
				$filter = $this->filterProvider->getFilter( $targetLang, $name );

				if ( $filter ) {
					$new_value = $filter->translate_by_name_and_context( $value, $name, $wpmlContext, $hasTranslation );

					if ( $hasTranslation ) {
						$value = $new_value;
					}
				}
			}
		}

		$this->lock = false;

		return $value;
	}

	/**
	 * @param string $domain
	 * @param string $locale
	 */
	private function loadTextDomain( $domain, $locale ) {
		if (
			! isset( $GLOBALS['l10n'][ $domain ] )
			&& ! isset( $GLOBALS['l10n_unloaded'][ $domain ] )
			&& ! isset( self::$loadedDomains[ $locale ][ $domain ] )
		) {
			load_textdomain(
				$domain,
				$this->fileManager->getFilepath( $domain, $locale )
			);

			self::$loadedDomains[ $locale ][ $domain ] = true;
		}
	}

	/**
	 * @param string   $targetLang
	 * @param callable $function
	 *
	 * @return string
	 */
	private function withMOLocale( $targetLang, $function ) {
		$initialLocale = $this->languageSwitch->getCurrentLocale();

		if ( $targetLang ) {
			$targetLocale = $this->locale->get_locale( $targetLang );
			$this->languageSwitch->switchToLocale( $targetLocale );
			$result = $function( $targetLocale );
			$this->languageSwitch->switchToLocale( $initialLocale );
		} else {
			$result = $function( $initialLocale );
		}

		return $result;
	}

	/**
	 * @param string $name
	 * @param string $gettextContext
	 *
	 * @return string
	 */
	public static function getCustomContext( $name, $gettextContext ) {
		return 'wpml#' . $name . '#' . $gettextContext;
	}

	/**
	 * We will allow MO translation only when
	 * the original is not empty.
	 *
	 * We also need to make sure we deal with a
	 * WPML registered string (not gettext).
	 *
	 * If those conditions are not fulfilled,
	 * we will translate from the database.
	 *
	 * @param string $original
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function canTranslateWithMO( $original, $name ) {
		return $original && self::isWpmlRegisteredString( $original, $name );
	}

	/**
	 * This allows to differentiate WPML registered strings
	 * from gettext strings that have the default hash for
	 * the name.
	 *
	 * But it's still possible that WPML registered strings
	 * have a hash for the name.
	 *
	 *
	 * @param string $original
	 * @param string $name
	 *
	 * @return bool
	 */
	private static function isWpmlRegisteredString( $original, $name ) {
		return $name && md5( $original ) !== $name;
	}

	public static function resetCache() {
		self::$loadedDomains = [];
	}
}
