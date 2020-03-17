<?php

namespace WPML\ST\MO\File;


use WPML\ST\TranslationFile\StringEntity;

class Generator {
	/** @var MOFactory */
	private $moFactory;

	public function __construct( MOFactory $moFactory ) {
		$this->moFactory = $moFactory;
	}

	/**
	 * @param StringEntity[] $entries
	 *
	 * @return string
	 */
	public function getContent( array $entries ) {
		$mo = $this->moFactory->createNewInstance();
		\wpml_collect( $entries )->map( [ $this, 'mapEntry' ] )->each( function ( array $args ) use ( $mo ) {
			$mo->add_entry( $args );
		} );

		$mem_file = fopen( 'php://memory', 'r+' );
		$mo->export_to_file_handle( $mem_file );

		rewind( $mem_file );
		$mo_content = stream_get_contents( $mem_file );

		fclose( $mem_file );

		return $mo_content;
	}

	/**
	 * @param StringEntity $entry
	 *
	 * @return array
	 */
	public function mapEntry( StringEntity $entry ) {
		$args = [
			'singular'     => $entry->get_original(),
			'translations' => $entry->get_translations(),
			'context'      => $entry->get_context(),
			'plural'       => $entry->get_original_plural(),
		];

		return $args;
	}
}
