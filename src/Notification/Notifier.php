<?php
/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */

namespace BannerMonitor\Notification;


interface Notifier {

	/**
	 * @param string $subject
	 * @param string $body
	 * @return boolean
	 */
	public function notifyAdmin ( $subject, $body );
}