<?php

namespace BannerMonitor;

use Symfony\Component\Console\Application;
use FileFetcher\SimpleFileFetcher;
use BannerMonitor\Commands\CheckBannersCommand;
use BannerMonitor\Config\ConfigFetcher;
use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher;
use BannerMonitor\Notification\SwiftMailNotifier;
use Swift_MailTransport;
use Swift_Mailer;


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

		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance( $transport );
		$notifier = new SwiftMailNotifier( $mailer, MAIL_RECEIVER, array( MAIL_SENDER_EMAIL => MAIL_SENDER_NAME ) );

		$command->setDependencies( $configFetcher, $bannerMonitor, $notifier );

		$app->add( $command );
	}

}
