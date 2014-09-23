<?php
use BannerMonitor\Config\ConfigFetcher;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class ConfigFetcherTest extends \PHPUnit_Framework_TestCase {

	private $_rootDir;

	protected function setUp() {
		vfsStream::setup( 'test' );
		$this->_rootDir = vfsStream::url( 'test' );
	}

	public function testInvalidFilename_ReturnsFalse() {
		$confFilename = 'test.yml';
		$this->mockFile( $confFilename );

		$configFetcher = new ConfigFetcher();

		$this->assertFalse( $configFetcher->fetchConfig( $this->_rootDir . DIRECTORY_SEPARATOR . $confFilename ) );
	}

	public function testInvalidFileContent_ReturnsFalse() {
		$confFilename = 'test.yaml';
		$this->mockFileWithContent( $confFilename, 'adfdafsaf' );

		$configFetcher = new ConfigFetcher();

		$this->assertFalse( $configFetcher->fetchConfig( $this->_rootDir . DIRECTORY_SEPARATOR . $confFilename ) );
	}

	public function testValidFileContent_ReturnsContent() {
		$confFilename = 'test.yaml';
		$contentMock = "
centralnoticeallocations:
  project: wikipedia
  country: DE
  language: de
";
		$output = array(
			'project' => 'wikipedia',
			'country' => 'DE',
			'language' => 'de',
		);

		$this->mockFileWithContent( $confFilename, $contentMock );

		$configFetcher = new ConfigFetcher();

		$this->assertSame( $configFetcher->fetchConfig( $this->getMockFilePath( $confFilename ) ), $output );
	}

	private function getMockFilePath( $fileName ) {
		return $this->_rootDir . DIRECTORY_SEPARATOR . $fileName;
	}

	private function mockFile( $fileName ) {
		$this->mockFileWithContent( $fileName, '' );
	}

	private function mockFileWithContent( $fileName, $content ) {
		$file = vfsStream::newFile( $fileName );
		$file->setContent( $content );

		vfsStreamWrapper::getRoot()->addChild( $file );
	}
}

