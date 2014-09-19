<?php

use BannerMonitor\BannerMonitor;
use BannerMonitor\Banner;

/**
 * @covers BannerMonitor
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class BannerMonitorTest extends PHPUnit_Framework_TestCase  {

    private function newBannerMonitor () {
        $fetcher = $this->getMock( 'FileFetcher\FileFetcher' );
        $banner = new Banner();
        $banner->name = 'wlm_2014';

        $bannersToMonitor = array( $banner );

        return new BannerMonitor( $fetcher, $bannersToMonitor );
    }

    public function testCanConstructBannerMonitor() {
        $fetcher = $this->getMock( 'FileFetcher\FileFetcher' );

        new BannerMonitor( $fetcher, array() );
        $this->assertTrue( true );
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testFetcherReturnsInvalidData_MonitorReturnsFalse( $input, $result ) {
        $bannerMonitor = $this->newBannerMonitor();

        $output = $bannerMonitor->getBanners( $input );
        $this->assertSame($output, $result );
    }

    public function invalidDataProvider()
    {
        return array(
            array('', false),
            array('{asfasfas', false),
            array('{"foo":"bar"}', false),
        );
    }

    public function testFetcherReturnsCentralNoticeBanner_MonitorReturnsBannerArray() {
        $bannerMonitor = $this->newBannerMonitor();

        $result = $bannerMonitor->getBanners( '{"centralnoticeallocations":{"banners":[]}}' );

        $bannerResult = array();
        $this->assertSame($result, $bannerResult );
    }

    public function testConfiguredBannerIsNotLive_MissingFilterReturnsMissingBanners() {
        $bannerMonitor = $this->newBannerMonitor();

        $result = $bannerMonitor->filterMissingBanners( array() );

        $banner = new Banner();
        $banner->name = 'wlm_2014';
        $monitorResult = array( 0 => $banner );

        $this->assertEquals($result, $monitorResult );
    }

    public function testWMDEBannerIsLive_BannerFilterReturnsBanner() {
        $bannerMonitor = $this->newBannerMonitor();

        $banner = new Banner();
        $banner->name = 'C14_WMDE';
        $filterInput = array( 0 => $banner );

        $result = $bannerMonitor->filterWMDEBanners( $filterInput );

        $filterOutput = array( 0 => $banner );

        $this->assertEquals($result, $filterOutput );
    }
}