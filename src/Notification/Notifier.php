<?php
/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */

namespace BannerMonitor\Notification;


interface Notifier {

	public function notifyAdmin ( $subject, $body );
}