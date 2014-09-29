<?php
use BannerMonitor\Config\ConfigFetcher;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use FileFetcher\SimpleFileFetcher;

/**
 * @covers BannerMonitor\Config\ConfigFetcher
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class ConfigFetcherTest extends \PHPUnit_Framework_TestCase {

	private $rootDir;

	protected function setUp() {
		vfsStream::setup( 'test' );
		$this->rootDir = vfsStream::url( 'test' );
	}

	public function testInvalidFilename_ReturnsFalse() {
		$confFilename = 'test.yml';
		$this->mockFile( $confFilename );

		$configFetcher = $this->setUpFetcher();

		$this->assertFalse( $configFetcher->fetchConfig( $this->getMockFilePath( $confFilename ) ) );
	}

	public function testInvalidFileContent_ReturnsFalse() {
		$confFilename = 'test.yaml';
		$this->mockFileWithContent( $confFilename, 'adfdafsaf' );

		$configFetcher = $this->setUpFetcher();

		$this->assertFalse( $configFetcher->fetchConfig( $this->getMockFilePath( $confFilename ) ) );
	}

	public function testValidFileContent_ReturnsContent() {
		$confFilename = 'test.yaml';
		$contentMock = "
centralnoticeallocations:
  project: wikipedia
  country: DE
  language: de
  checkForLiveBanners:
    banner1: { bannerId: B14_WMDE_140925_ctrl, start: 2014-09-25 14:30, end: 2014-10-02 14:30 }
";
		$output = array(
			'project' => 'wikipedia',
			'country' => 'DE',
			'language' => 'de',
			'checkForLiveBanners' => array(
				'banner1' => array( 'bannerId' => 'B14_WMDE_140925_ctrl',
					'start' => '2014-09-25 14:30',
					'end' => '2014-10-02 14:30'
				)
			)
		);

		$this->mockFileWithContent( $confFilename, $contentMock );

		$configFetcher = $this->setUpFetcher();

		$this->assertSame( $configFetcher->fetchConfig( $this->getMockFilePath( $confFilename ) ), $output );
	}

	public function testInValidDates_ReturnsFalse() {
		$confFilename = 'test.yaml';
		$contentMock = "
centralnoticeallocations:
  checkForLiveBanners:
    banner1: { bannerId: B14_WMDE_140925_ctrl, start: 2014-09-25, end: 2014-10-02 14:30 }
";
		$this->mockFileWithContent( $confFilename, $contentMock );

		$configFetcher = $this->setUpFetcher();

		$this->setExpectedException( 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException' );

		$configFetcher->fetchConfig( $this->getMockFilePath( $confFilename ) );
	}

	private function setUpFetcher() {
		$fileFetcher = new SimpleFileFetcher();
		return new ConfigFetcher( $fileFetcher );
	}

	private function getMockFilePath( $fileName ) {
		return $this->rootDir . DIRECTORY_SEPARATOR . $fileName;
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

