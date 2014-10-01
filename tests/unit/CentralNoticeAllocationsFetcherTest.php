<?php

use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher;

/**
 * @covers BannerMonitor\CentralNoticeAllocationsFetcherTest
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class CentralNoticeAllocationsFetcherTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider invalidDataProvider
	 */
	public function testFetcherReturnsInvalidData_CentralNoticeAllocationsReturnsFalse( $input ) {
		$filterMock = $this->getMock( 'BannerMonitor\CentralNoticeAllocations\CentralNoticeApiFilter' );

		$allocationsFetcher = $this->newCentralNoticeAllocationsFetcher( 'http://www.example.com/w/api.php?action=centralnoticeallocations&format=json', $input );

		$this->assertFalse( $allocationsFetcher->fetchBannersLive( $filterMock ) );
	}

	public function invalidDataProvider() {
		return array(
			array( '<?xml' ),
			array( '<!DOCTYPE html' ),
			array( '{"servedby":"mw1125","error":{' ),
			array( '' ),
		);
	}

	private function newCentralNoticeAllocationsFetcher( $fetcherInputUrl, $fetcherReturnValue ) {
		$fileFetcherMock = $this->getMock( 'FileFetcher\FileFetcher' );

		$fileFetcherMock->expects( $this->once() )
			->method( 'fetchFile' )
			->with( $this->equalTo( $fetcherInputUrl ) )
			->will( $this->returnValue( $fetcherReturnValue ) );

		return new CentralNoticeAllocationsFetcher( 'http://www.example.com/w/api.php', $fileFetcherMock );
	}

}