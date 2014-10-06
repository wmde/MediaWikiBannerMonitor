<?php

namespace BannerMonitor\Commands;

use BannerMonitor\BannerMonitor;
use BannerMonitor\Config\ConfigFetcher;
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

	public function setDependencies( ConfigFetcher $configFetcher, BannerMonitor $bannerMonitor ) {
		$this->configFetcher = $configFetcher;
		$this->bannerMonitor = $bannerMonitor;
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$confFile = $input->getArgument( 'config-file' );

		$output->writeln( 'Checking banners' );

		if( $confFile ) {
			$output->writeln( '...with file ' . $confFile );
			$configValues = $this->configFetcher->fetchConfig( $confFile );
			$missingBanners = $this->bannerMonitor->getMissingBanners( $configValues['banners'] );
		} else {
			retrun - 1;
		}

		if( $input->getOption( 'notify-mail' ) ) {
			$output->writeln( '...with notify-mail option' );

		} else {
			$output->writeln( 'missing banners:' );
			$output->writeln( json_encode( $missingBanners ) );
		}
	}

}