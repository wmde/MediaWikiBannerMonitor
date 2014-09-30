<?php

namespace BannerMonitor;

use BannerMonitor\CentralNoticeAllocations\CentralNoticeAllocationsFetcher;
use BannerMonitor\CentralNoticeAllocations\CentralNoticeApiFilter;


/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class BannerMonitor {

	private $centralNoticeFetcher;

	public function __construct( CentralNoticeAllocationsFetcher $centralNoticeFetcher ) {
		$this->centralNoticeFetcher = $centralNoticeFetcher;
	}

	public function getMissingBanners( $bannersToLookFor ) {
		$missingBanners = array();

		if( empty( $bannersToLookFor ) ) {
			return $missingBanners;
		}

		foreach( $bannersToLookFor as $name => $banner ) {
			$apiFilterOptions = $this->aggregateFilter( $banner );
			$bannersLive = $this->centralNoticeFetcher->fetchBannersLive( $apiFilterOptions );

			if( !$this->isBannerInArray( $name, $bannersLive ) ) {
				$missingBanners[$name] = $banner;
			}

		}

		return $missingBanners;
	}

	private function aggregateFilter( $banner ) {
		$filter = new CentralNoticeApiFilter();
		$filter->project = $banner['project'];
		$filter->country = $banner['country'];
		$filter->language = $banner['language'];
		$filter->anonymous = $banner['anonymous'];
		$filter->device = $banner['device'];
		$filter->bucket = $banner['bucket'];

		return $filter;
	}

	private function isBannerInArray( $bannerNeedle, $bannerHaystack ) {
		$result = false;

		foreach( $bannerHaystack as $banner ) {
			if( $banner->name == $bannerNeedle ) {
				return true;
			}
		}

		return $result;
	}
}