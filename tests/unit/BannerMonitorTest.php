<?php

use BannerMonitor\BannerMonitor;
use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsBanner;
use BannerMonitor\CentralNoticeAllocations\CentralNoticeApiFilter;

/**
 * @covers BannerMonitor\BannerMonitor
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class BannerMonitorTest extends PHPUnit_Framework_TestCase {

	public function testFetcherError_MissingBannersReturnsFalse() {
		$bannerMonitor = $this->newBannerMonitor( $this->mockFilter(), false );
		$bannersToMonitor = array(
			'B14_WMDE_140925_ctrl' => array(
				'project' => 'wikipedia',
				'country' => 'DE',
				'language' => 'de',
				'anonymous' => true,
				'device' => 'desktop',
				'bucket' => 0,
				'start' => '2014-09-25 14:30',
				'end' => '2014-10-02 14:30'
			)
		);

		$this->assertFalse( $bannerMonitor->getMissingBanners( $bannersToMonitor ));
	}

	public function testNoBannersToMonitor_ReturnsEmptyArray() {
		$centralNoticeFetcherMock = $this->getMockBuilder( 'BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher' )->disableOriginalConstructor()->getMock();
		$bannerMonitor = new BannerMonitor( $centralNoticeFetcherMock );
		$bannersToMonitor = array();

		$this->assertSame( $bannerMonitor->getMissingBanners( $bannersToMonitor ), array() );
	}

	public function testNoBannersLive_MissingBannersReturnsInputArray() {
		$filter = new CentralNoticeApiFilter();
		$filter->project = 'wikipedia';
		$filter->country = 'DE';
		$filter->language = 'de';
		$filter->anonymous = true;
		$filter->device = 'desktop';
		$filter->bucket = 0;

		$bannerMonitor = $this->newBannerMonitor( $filter, array() );
		$bannersToMonitor = array(
			'B14_WMDE_140925_ctrl' => array(
				'project' => 'wikipedia',
				'country' => 'DE',
				'language' => 'de',
				'anonymous' => true,
				'device' => 'desktop',
				'bucket' => 0,
				'start' => '2014-09-25 14:30',
				'end' => '2014-10-02 14:30'
			)
		);

		$this->assertSame( $bannerMonitor->getMissingBanners( $bannersToMonitor ), $bannersToMonitor );
	}

	public function testSomeBannersLive_MissingBannersReturnsMissingBannersArray() {
		$bannerMonitor = $this->newBannerMonitor( $this->mockFilter(), array( $this->mockBanner( 'B14_WMDE_140925_ctrl' ) ) );
		$bannersToMonitor = array(
			'B14_WMDE_140925_ctrl' => array(
				'project' => 'wikipedia',
				'country' => 'DE',
				'language' => 'de',
				'anonymous' => true,
				'device' => 'desktop',
				'bucket' => 0,
				'start' => '2014-09-25 14:30',
				'end' => '2014-10-02 14:30'
			),
			'B14_WMDE_140925_switch' => array(
				'project' => 'wikipedia',
				'country' => 'DE',
				'language' => 'de',
				'anonymous' => true,
				'device' => 'desktop',
				'bucket' => 0,
				'start' => '2014-09-25 14:30',
				'end' => '2014-10-02 14:30'
			)
		);

		$bannersMissing = $bannersToMonitor;
		unset( $bannersMissing['B14_WMDE_140925_ctrl'] );

		$this->assertSame( $bannerMonitor->getMissingBanners( $bannersToMonitor ), $bannersMissing );
	}

	private function newBannerMonitor( $fetcherInputFilter, $fetcherReturnValue ) {
		$centralNoticeFetcherMock = $this->getMockBuilder( 'BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher' )->disableOriginalConstructor()->getMock();

		$centralNoticeFetcherMock->method( 'fetchBannersLive' )
			->with( $this->equalTo( $fetcherInputFilter ) )
			->will( $this->returnValue( $fetcherReturnValue ) );

		return new BannerMonitor( $centralNoticeFetcherMock );
	}

	private function mockBanner( $name ) {
		$banner = new CentralNoticeAllocationsBanner();
		$banner->name = $name;

		return $banner;
	}

	private function mockFilter() {
		$filter = new CentralNoticeApiFilter();
		$filter->project = 'wikipedia';
		$filter->country = 'DE';
		$filter->language = 'de';
		$filter->anonymous = true;
		$filter->device = 'desktop';
		$filter->bucket = 0;

		return $filter;
	}
}
