<?php

namespace BannerMonitor;

use BannerMonitor\Commands\CheckBannersCommand;
use Symfony\Component\Console\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Factory {

	public function newCliApplication() {
		$application = new Application();

		$application->setName( 'Banner monitor' );
		$application->setVersion( '0.1' );

		$application->add( new CheckBannersCommand() );

		return $application;
	}

} 