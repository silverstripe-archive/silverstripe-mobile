<?php
class MobileBrowserDetectorTest extends SapphireTest {
	
	public function testIsTablet() {
		$this->assertTrue(MobileBrowserDetector::is_tablet(
			'Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13',
			'XOOM tablet, contains "android" but not "mobile"'
		));
		$this->assertFalse(MobileBrowserDetector::is_tablet(
			'Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; Nexus One Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Nexus One, contains "mobile" and "android"'
		));
		// This is where this approach falls down ... can't detect MS Surface usage.
		// See http://www.brettjankord.com/2013/01/10/active-development-on-categorizr-has-come-to-an-end/
		$this->assertFalse(MobileBrowserDetector::is_tablet(
			'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; ARM; Trident/6.0; Touch)'
		));
	}
}