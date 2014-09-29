<?php

namespace BannerMonitor;

use BannerMonitor\Commands\CheckBannersCommand;
use BannerMonitor\Config\ConfigFetcher;
use Symfony\Component\Console\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Factory {

	public function newCliApplication() {
		$app = new Application();

		$app->setName( 'Banner monitor' );
		$app->setVersion( '0.1' );

		$this->addCheckBannersCommandTo( $app );

		return $app;
	}

	private function addCheckBannersCommandTo( Application $app ) {
		$command = new CheckBannersCommand();

		$command->setDependencies();

		$app->add( $command );
	}

}
