<?php

use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher;

/**
 * @covers BannerMonitor\CentralNoticeAllocationsFetcherTester
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class CentralNoticeAllocationsFetcherTester extends PHPUnit_Framework_TestCase  {

    public function testFetcherReturnsInvalidHtml_CentralNoticeAllocationsReturnsFalse() {
		$filterMock = $this->getMock( 'BannerMonitor\CentralNoticeAllocations\CentralNoticeApiFilter' );

		$allocationsFetcher = $this->newCentralNoticeAllocationsFetcher( 'http://www.example.com/w/api.php?action=centralnoticeallocations&format=json', '<!DOCTYPE html' );

		$this->assertFalse( $allocationsFetcher->fetchBannersLive( $filterMock ) );
    }

	public function testFetcherReturnsFalseJson_CentralNoticeAllocationsReturnsFalse() {
		$filterMock = $this->getMock( 'BannerMonitor\CentralNoticeAllocations\CentralNoticeApiFilter' );

		$allocationsFetcher = $this->newCentralNoticeAllocationsFetcher( 'http://www.example.com/w/api.php?action=centralnoticeallocations&format=json', '{"servedby":"mw1125","error":{' );

		$this->assertFalse( $allocationsFetcher->fetchBannersLive( $filterMock ) );
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