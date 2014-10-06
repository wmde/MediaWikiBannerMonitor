<?php

namespace BannerMonitor;

use BannerMonitor\Notification\SwiftMailNotifier;

/**
 * @covers BannerMonitor\Notification\SwiftMailNotifier
 *
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class SwiftMailNotifierTest extends \PHPUnit_Framework_TestCase {

	public function testNotifyWithNoReceiver_notifyAdminReturnsFalse() {
		$mailer = $this->getMockBuilder( 'Swift_Mailer' )->disableOriginalConstructor()->getMock();

		$mailer->expects( $this->once() )
			->method( 'send' )
			->will( $this->returnValue( 0 ) );

		$swiftMailer = new SwiftMailNotifier( $mailer, array(), 'test@example.com' );

		$this->assertFalse( $swiftMailer->notifyAdmin( '', '' ) );
	}
}
