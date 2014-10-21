<?php

namespace BannerMonitor\Config;

use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use FileFetcher\FileFetcher;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class ConfigFetcher {

	private $fetcher;

	public function __construct( FileFetcher $fetcher ) {
		$this->fetcher = $fetcher;
	}

	/**
	 * @param string $fileName
	 * @return array|bool
	 */
	public function fetchConfig( $fileName ) {
		$content = $this->fetchConfigContent( $fileName );

		$configValues = $this->parseConfigContent( $content );

		if( !is_array( $configValues ) ) {
			return false;
		}

		$configuration = $this->validateConfiguration( $configValues );

		if( $configuration === null ) {
			return false;
		}

		return $configuration;
	}

	private function fetchConfigContent( $fileName ) {
		if( !file_exists( $fileName ) ) {
			return false;
		}
		return $this->fetcher->fetchFile( $fileName );
	}

	private function parseConfigContent( $content ) {
		return YAML::parse( $content );
	}

	private function validateConfiguration( $configValues ) {
		$processor = new Processor();
		$configuration = new Configuration();

		try {
			$processedConfiguration = $processor->processConfiguration(
				$configuration,
				$configValues
			);
		} catch( Exception $e ) {
			return false;
		}

		return $processedConfiguration;
	}
} 