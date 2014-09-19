<?php

namespace BannerMonitor;

use FileFetcher\FileFetcher;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class BannerMonitor {
    private $fetcher;
    private $bannersMonitored;

    public function __construct (FileFetcher $fetcher, $bannersMonitored) {
        $this->fetcher = $fetcher;
        $this->bannersMonitored = $bannersMonitored;
    }

    public function bannerLiveCheck ( )
    {
        $metaResult = $this->fetcher->fetchFile( "https://meta.wikimedia.org/w/api.php?action=centralnoticeallocations&format=json&project=wikipedia&country=DE&language=de" );
        $bannerLiveArray = $this->getBanners( $metaResult );

        if($bannerLiveArray === false) {
            return false;
        } elseif ( count( $bannerLiveArray ) == 0 ) {
            $this->notifyFundraisingTeam();
            return 0;
        }

        $missingBanners = $this->filterMissingBanners( $bannerLiveArray );

        if( $missingBanners ) {
            $this->notifyFundraisingTeam( $missingBanners );
        }

    }

    public function getBanners( $string )
    {
        $jsonResponse = json_decode($string);

        if( $jsonResponse === null ) {
            return false;
        }

        if( !isset($jsonResponse->centralnoticeallocations) || !is_array($jsonResponse->centralnoticeallocations->banners) ) {
            return false;
        }

        return $jsonResponse->centralnoticeallocations->banners;
    }

    public function filterMissingBanners( $bannersLiveArray )
    {
        $missingBanners = array();
        foreach( $this->bannersMonitored as $checkBanner ) {
            if(!$this->isBannerInArray( $checkBanner, $bannersLiveArray )) {
                $missingBanners[] = $checkBanner;
            }
        }

        return $missingBanners;
    }

    public function filterWMDEBanners( $bannersLiveArray )
    {
        $wmdeBanners = array();
        foreach( $bannersLiveArray as $checkBanner ) {
            if( preg_match( '/^C[0-9]{2}_WMDE/', $checkBanner->name) ) {
                $wmdeBanners[] = $checkBanner;
            }
        }

        return $wmdeBanners;
    }



    private function isBannerInArray ( $bannerNeedle, $bannerHaystack ) {
        $result = false;

        foreach( $bannerHaystack as $banner ) {
            if( $banner->name == $bannerNeedle->name ) {
                return true;
            }
        }

        return $result;
    }
}