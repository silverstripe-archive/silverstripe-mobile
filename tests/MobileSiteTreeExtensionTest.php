<?php
/**
 * @package mobile
 * @subpackage tests
 */
class MobileSiteTreeExtensionTest extends FunctionalTest {
	
	public static $fixture_file = 'mobile/tests/MobileSiteControllerExtensionTest.yml';

	public static $use_draft_site = true;

	protected $autoFollowRedirection = false;
	
	protected $requiredExtensions = array(
		'SiteTree' => array('MobileSiteTreeExtension'),
		'SiteConfig' => array('MobileSiteConfigExtension'),
	);

	public function setUp() {
		parent::setUp();
		MobileSiteConfigExtension::set_theme_copy_path(TEMP_FOLDER . '/mobile-test-copy-theme/');
	}

	public function testShowsCanonicalLinkOnMobile() {
		$page = $this->objFromFixture('Page', 'page');
		
		$config = SiteConfig::current_site_config();
		$config->MobileDomain = 'http://m.test.com';
		$config->FullSiteDomain = 'http://test.com';
		$config->MobileSiteType = 'RedirectToDomain';
		$config->write();
		
		$origHost = $_SERVER['HTTP_HOST'];
		
		$_SERVER['HTTP_HOST'] = 'test.com';
		$response = $this->get($page->RelativeLink());
		$canonicalEls = $this->cssParser()->getByXpath('//link[@rel=\'canonical\']');
		$this->assertTrue(empty($canonicalEls), 'Canonical links not included on desktp');

		$_SERVER['HTTP_HOST'] = 'm.test.com';
		$response = $this->get($page->RelativeLink());
		$canonicalEls = $this->cssParser()->getByXpath('//link[@rel=\'canonical\']');
		$this->assertFalse(empty($canonicalEls), 'Canonical links included on mobile');
		$this->assertEquals('http://test.com/page/', (string)$canonicalEls[0]['href'], 'Canonical link matches correct page');
		
		$_SERVER['HTTP_HOST'] = $origHost;
	}

}