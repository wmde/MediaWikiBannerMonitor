<?php

namespace BannerMonitor\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class ConfigFetcher {

	public function fetchConfig( $fileName ) {
		$configValues = $this->fetchConfigValues( $fileName );

		if( !is_array( $configValues ) ) {
			return false;
		}

		$configuration = $this->validateConfiguration( $configValues );

		if( $configuration === null ) {
			return false;
		}

		return $configuration;
	}

	private function fetchConfigValues( $fileName ) {
		$locator = new FileLocator();

		$loader = new YamlConfigLoader( $locator );
		$configValues = $loader->load( $locator->locate( $fileName ) );

		return $configValues;
	}

	private function validateConfiguration( $configValues ) {
		$processor = new Processor();
		$configuration = new Configuration();

		$processedConfiguration = $processor->processConfiguration(
			$configuration,
			$configValues
		);

		return $processedConfiguration;
	}
} 