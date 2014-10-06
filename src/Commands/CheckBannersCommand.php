<?php

namespace BannerMonitor\Commands;

use BannerMonitor\BannerMonitor;
use BannerMonitor\Config\ConfigFetcher;
use BannerMonitor\Notification\Notifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Kai Nissen
 * @author Christoph Fischer
 */
class CheckBannersCommand extends Command {

	private $configFetcher;
	private $bannerMonitor;
	private $notifier;

	protected function configure() {
		$this
			->setName( 'checkbanners' )
			->setDescription( 'Checks the banners configured in the given config file' )
			->addArgument(
				'config-file',
				InputArgument::REQUIRED,
				'configuration file for the banner checks'
			)
			->addOption(
				'notify-mail',
				null,
				InputOption::VALUE_NONE,
				'notify by mail'
			);
	}

	public function setDependencies( ConfigFetcher $configFetcher, BannerMonitor $bannerMonitor, Notifier $notifier ) {
		$this->configFetcher = $configFetcher;
		$this->bannerMonitor = $bannerMonitor;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$confFile = $input->getArgument( 'config-file' );
		$outputLines = array();

		$outputLines[] = 'Checking banners';

		if( !$confFile ) {
			$outputLines[] = 'Error reading config-file...';
			$output->writeln( $outputLines );
			return -1;
		}

		$outputLines[] = '...with file ' . $confFile;
		$configValues = $this->configFetcher->fetchConfig( $confFile );
		$missingBanners = $this->bannerMonitor->getMissingBanners( $configValues['banners'] );

		if( !is_array( $missingBanners ) ) {
			$outputLines[] = 'Error getting Banners...';
			$output->writeln( $outputLines );
			return -1;
		}

		if( count( $missingBanners ) === 0 ) {
			$outputLines[] = 'No Banners Missing.';
			$output->writeln( $outputLines );
			return -1;
		}

		$outputLines[] = 'Banners Missing:';
		$outputLines[] = json_encode( $missingBanners );

		if( $input->getOption( 'notify-mail' ) ) {
			$outputLines[] = '...with notify-mail option';

			$subject = 'Missing Banners:';
			$this->notifier->notify( $subject, $output );
		}

		$output->writeln( $outputLines );
	}

}