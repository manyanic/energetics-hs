<?php

namespace WPML\ST\MO\Generate\Process;

use WPML\ST\MO\File\ManagerFactory;
use WPML\ST\MO\Generate\MultiSite\Condition;
use WPML\Utils\Pager;
use function WPML\Container\make;

class ProcessFactory {
	const FILES_PAGER     = 'wpml-st-mo-generate-files-pager';
	const FILES_PAGE_SIZE = 20;
	const SITES_PAGER     = 'wpml-st-mo-generate-sites-pager';

	/** @var Condition */
	private $multiSiteCondition;

	/**
	 * @param Condition $multiSiteCondition
	 */
	public function __construct( Condition $multiSiteCondition = null ) {
		$this->multiSiteCondition = $multiSiteCondition ?: new Condition();
	}

	/**
	 * @return Process
	 * @throws \Auryn\InjectionException
	 */
	public function create() {
		$singleSiteProcess = make(
			SingleSiteProcess::class,
			[
				':pager'             => new Pager( self::FILES_PAGER, self::FILES_PAGE_SIZE ),
				':manager'           => ManagerFactory::create(),
				':migrateAdminTexts' => \WPML_Admin_Texts::get_migrator(),
			]
		);

		if ( $this->multiSiteCondition->shouldRunWithAllSites() ) {
			return make( MultiSiteProcess::class,
				[ ':singleSiteProcess' => $singleSiteProcess, ':pager' => new Pager( self::SITES_PAGER, 1 ) ]
			);
		} else {
			return $singleSiteProcess;
		}
	}

}
