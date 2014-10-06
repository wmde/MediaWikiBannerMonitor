<?php

namespace BannerMonitor;

use Symfony\Component\Console\Application;
use FileFetcher\SimpleFileFetcher;
use BannerMonitor\Commands\CheckBannersCommand;
use BannerMonitor\Config\ConfigFetcher;
use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Christoph Fischer
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

		$fileFetcher = new SimpleFileFetcher();
		$configFetcher = new ConfigFetcher( $fileFetcher );

		$caFetcher = new CentralNoticeAllocationsFetcher( META_API_URL, $fileFetcher );
		$bannerMonitor = new BannerMonitor( $caFetcher );

		$command->setDependencies( $configFetcher, $bannerMonitor );

		$app->add( $command );
	}

}
