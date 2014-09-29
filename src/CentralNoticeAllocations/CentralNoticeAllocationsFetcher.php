<?php

namespace BannerMonitor\CentralNoticeAllocations;

use FileFetcher\FileFetcher;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class CentralNoticeAllocationsFetcher {

	private $apiBaseUrl;
	private $fileFetcher;

	public function __construct( $apiBaseUrl, FileFetcher $fetcher ) {
		$this->fileFetcher = $fetcher;
		$this->apiBaseUrl = $apiBaseUrl;
	}

	public function fetchBannersLive( CentralNoticeApiFilter $filter ) {
		$content = $this->fetchContent( $filter );

		if( $content === false ) {
			return false;
		}

		return $this->getBanners( $content );
	}

	private function fetchContent( CentralNoticeApiFilter $filter ) {
		$fetchUrl = $this->buildUrl( $filter );
		$content = $this->fileFetcher->fetchFile( $fetchUrl );

		return $this->checkForCleanResult( $content );
	}

	private function checkForCleanResult( $content ) {
		if( preg_match( '/^(<!DOCTYPE html)|(<\?xml version="1.0")/', $content ) ) {
			return false;
		}

		return $content;
	}

	private function buildUrl( CentralNoticeApiFilter $filter ) {
		$getParameter = array(
			'action' => 'centralnoticeallocations',
			'format' => 'json',
		);
		$getParameter = array_merge( $getParameter, get_object_vars( $filter ) );

		return $this->apiBaseUrl . '?' . http_build_query( $getParameter );
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
}