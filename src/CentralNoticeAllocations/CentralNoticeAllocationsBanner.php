<?php

namespace BannerMonitor\CentralNoticeAllocations;

/**
 * representing the banner objects returned by CentralNotice allocations
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */

class CentralNoticeAllocationsBanner {
	public $name;
	public $weight;
	public $display_anon;
	public $display_account;
	public $fundraising;
	public $autolink;
	public $landing_pages;
	public $device;
	public $campaign;
	public $campaign_z_index;
	public $campaign_num_buckets;
	public $campaign_throttle;
	public $bucket;
	public $max_allocation;
	public $allocation;
	public $slots;
} 