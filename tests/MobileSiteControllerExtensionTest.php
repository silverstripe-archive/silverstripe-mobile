<?php
/**
 * @package mobile
 * @subpackage tests
 */
class MobileSiteControllerExtensionTest extends FunctionalTest {

	public static $fixture_file = 'mobile/tests/MobileSiteControllerExtensionTest.yml';

	public static $use_draft_site = true;

	protected static $originalUserAgent;

	protected static $originalHost;

	protected static $originalAccept;

	protected $autoFollowRedirection = false;

	public function setUp() {
		parent::setUp();
		if(!self::$originalUserAgent) self::$originalUserAgent = $_SERVER['HTTP_USER_AGENT'];
		if(!self::$originalHost) self::$originalHost = $_SERVER['HTTP_HOST'];
		if(!self::$originalAccept) self::$originalAccept = $_SERVER['HTTP_ACCEPT'];
	}

	public function tearDown() {
		parent::tearDown();
		$_SERVER['HTTP_USER_AGENT'] = self::$originalUserAgent;
		$_SERVER['HTTP_HOST'] = self::$originalHost;
		$_SERVER['HTTP_ACCEPT'] = self::$originalAccept;
	}

	public function testFullSiteLink() {
		$page = $this->objFromFixture('Page', 'page');
		$controller = new ContentController($page);
		$this->assertTrue(strpos($controller->FullSiteLink(), 'page/?fullSite=1') !== false);
	}

	public function testRedirectToMobileSite() {
		$page = $this->objFromFixture('Page', 'page');
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();

		// Test common mobile agent strings
		foreach(array(
			'Mozilla/5.0 (iPod; U; CPU iPhone OS 2_0 like Mac OS X; de-de) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5A347 Safari/525.20',
			'Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_1_2 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7D11 Safari/528.16',
			'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3',
			'Mozilla/5.0 (Linux; U; Android 2.1-update1; en-us; ADR6300 Build/ERE27) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17',
			'iPod',
			'iPhone',
			'Android',
			'palm os',
			'hiptop',
			'avantgo',
			'iemobile',
			'502i',
			'3gso',
			'6310',
			'windows ce; ppc;',
			'Stuff; iPhone; Version details here',
			'Stuff here; Android; Version details here',
			'Opera/9.50 (J2ME/MIDP; Opera Mini/4.0.10031/298; U; en)',
			'Opera/8.0.1 (J2ME/MIDP; Opera Mini/3.1.9427/1724; en; U; ssr)',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+'
		) as $agent) {
			$_SERVER['HTTP_USER_AGENT'] = $agent;
			$response = $this->get($page->URLSegment);
			$headers = $response->getHeaders();
			$this->assertEquals(302, $response->getStatusCode());
			$this->assertEquals('http://m.' . $_SERVER['HTTP_HOST'], $headers['Location']);
		}
	}

	public function testRedirectToMobileFromAcceptSetting() {
		$page = $this->objFromFixture('Page', 'page');
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();

		$_SERVER['HTTP_ACCEPT'] = 'text/vnd.wap.wml';
		$response = $this->get($page->URLSegment);
		$headers = $response->getHeaders();
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals('http://m.' . $_SERVER['HTTP_HOST'], $headers['Location']);
	}

	public function testRedirectToMobileFromWAPProfile() {
		$page = $this->objFromFixture('Page', 'page');
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();

		$_SERVER['HTTP_X_WAP_PROFILE'] = 1;
		$response = $this->get($page->URLSegment);
		$headers = $response->getHeaders();
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals('http://m.' . $_SERVER['HTTP_HOST'], $headers['Location']);
		unset($_SERVER['HTTP_X_WAP_PROFILE']);
	}

	public function testNoRedirectToMobile() {
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'MobileThemeOnly';
		$config->write();
		$page = $this->objFromFixture('Page', 'page');
		$_SERVER['HTTP_USER_AGENT'] = 'iPhone';
		$response = $this->get($page->URLSegment);
		$headers = $response->getHeaders();
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testRedirectToFullSiteFromMobile() {
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();
		$page = $this->objFromFixture('Page', 'page');
		$_SERVER['HTTP_HOST'] = 'm.' . $_SERVER['HTTP_HOST'];
		$response = $this->get($page->URLSegment . '?fullSite=1', null, null, array('fullSite' => 1));
		$headers = $response->getHeaders();
		$this->assertEquals(302, $response->getStatusCode());
		$this->assertEquals($config->FullSiteDomain, $headers['Location']);
	}

	public function testNoMobileRedirectWhenFullSiteSessionSetOnMobile() {
		$_SERVER['HTTP_USER_AGENT'] = 'Android';
		$config = SiteConfig::current_site_config();
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();
		$page = $this->objFromFixture('Page', 'page');
		$response = $this->get($page->URLSegment, null, null, array('fullSite' => 1));
		$headers = $response->getHeaders();
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testOnMobileSite() {
		$_SERVER['HTTP_HOST'] = str_replace('m.', '', $_SERVER['HTTP_HOST']);
		$controller = new ContentController();
		$this->assertFalse($controller->onMobileDomain());
		$_SERVER['HTTP_HOST'] = 'm.' . $_SERVER['HTTP_HOST'];
		$this->assertTrue($controller->onMobileDomain());
	}

	public function testDirectlyAccessingMobileSiteOnAnyDevice() {
		$_SERVER['HTTP_USER_AGENT'] = 'anything can be here';
		$_SERVER['HTTP_HOST'] = 'm.' . $_SERVER['HTTP_HOST'];
		$page = $this->objFromFixture('Page', 'page');
		$response = $this->get($page->URLSegment);
		$headers = $response->getHeaders();
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testIsiPhone() {
		$controller = new ContentController();
		$_SERVER['HTTP_USER_AGENT'] = 'something else';
		$this->assertFalse($controller->IsiPhone());
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPod; U; CPU iPhone OS 2_0 like Mac OS X; de-de) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5A347 Safari/525.20';
		$this->assertTrue($controller->IsiPhone());
		$_SERVER['HTTP_USER_AGENT'] = 'Something here; iPhone; Probably something else here';
		$this->assertTrue($controller->IsiPhone());
	}

	public function testIsAndroid() {
		$controller = new ContentController();
		$_SERVER['HTTP_USER_AGENT'] = 'something else';
		$this->assertFalse($controller->IsAndroid());
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; U; Android 2.1-update1; en-us; ADR6300 Build/ERE27) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17';
		$this->assertTrue($controller->IsAndroid());
		$_SERVER['HTTP_USER_AGENT'] = 'Something here; Android; Probably something else here';
		$this->assertTrue($controller->IsAndroid());
	}

	public function testIsOperaMini() {
		$controller = new ContentController();
		$_SERVER['HTTP_USER_AGENT'] = 'something else';
		$this->assertFalse($controller->IsOperaMini());
		$_SERVER['HTTP_USER_AGENT'] = 'Opera/9.50 (J2ME/MIDP; Opera Mini/4.0.10031/298; U; en)';
		$this->assertTrue($controller->IsOperaMini());
		$_SERVER['HTTP_USER_AGENT'] = 'Something here; Opera Mini; Probably something else here';
		$this->assertTrue($controller->IsOperaMini());
	}

	public function testIsBlackBerry() {
		$controller = new ContentController();
		$_SERVER['HTTP_USER_AGENT'] = 'something else';
		$this->assertFalse($controller->IsBlackBerry());
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+';
		$this->assertTrue($controller->IsBlackBerry());
		$_SERVER['HTTP_USER_AGENT'] = 'Something here; BlackBerry; Probably something else here';
		$this->assertTrue($controller->IsBlackBerry());
	}

}