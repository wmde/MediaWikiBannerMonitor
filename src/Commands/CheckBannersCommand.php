<?php

namespace BannerMonitor\Commands;

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
			)
		;
    }

	public function setDependencies( \BannerMonitor\Config\ConfigFetcher $configFetcher ) {
		$this->configFetcher = $configFetcher;
	}

    protected function execute( InputInterface $input, OutputInterface $output ) {
		$confFile = $input->getArgument( 'config-file' );

		$output->writeln( 'Checking banners' );

		if ( $confFile ) {
			$output->writeln( '...with file ' . $confFile );
		}

		if ( $input->getOption( 'notify-mail' ) ) {
			$output->writeln( '...with notify-mail option' );
		}
    }

}