<?php

namespace Tests\System\Wikibase\Query\Cli;

use BannerMonitor\Commands\CheckBannersCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CheckBannersCommandTest extends \PHPUnit_Framework_TestCase {


	private function getOutputForArguments( array $arguments ) {
		$command = new CheckBannersCommand();

		$command->setDependencies();

		$tester = new CommandTester( $command );
		$tester->execute( $arguments );

		return $tester->getDisplay();
	}

	public function testCheckBannersCommandWithDefaultArguments() {
		$output = $this->getOutputForArguments( array() );

		$this->assertContains( 'Done checking banners', $output );
	}

	public function testCheckBannersCommandWithConfigFileArgument() {
		$output = $this->getOutputForArguments( array( 'config-file' => 'banners.conf' ) );

		$this->assertContains( '...with file banners.conf', $output );
	}

	public function testCheckBannersCommandWithNotificationOption() {
		$output = $this->getOutputForArguments( array( 'config-file' => 'banners.conf', '--notify-mail' => true ) );

		$this->assertContains( '...with notify-mail option', $output );
	}

}
