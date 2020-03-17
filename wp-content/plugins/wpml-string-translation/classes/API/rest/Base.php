<?php

namespace WPML\ST\Rest;

abstract class Base implements \WPML\Rest\ITarget, \IWPML_Action {

	/** @var \WPML\Rest\Adaptor */
	private $adaptor;

	public function __construct( \WPML\Rest\Adaptor $adaptor ) {
		$this->adaptor = $adaptor;
		$adaptor->set_target( $this );
	}

	/**
	 * @return string
	 */
	public function get_namespace() {
		return 'wpml/st/v1';
	}

	public function add_hooks() {
		$this->adaptor->add_hooks();
	}
}