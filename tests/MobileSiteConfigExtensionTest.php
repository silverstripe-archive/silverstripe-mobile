<?php
/**
 * @package mobile
 * @subpackage tests
 */
class MobileSiteConfigExtensionTest extends SapphireTest {

	public function setUp() {
		parent::setUp();
		MobileSiteConfigExtension::set_theme_copy_path(TEMP_FOLDER . '/mobile-test-copy-theme/');
	}

	public function testRequireDefaultRecordsCopiesDefaultThemeWhenDefaultThemeSet() {
		$config = SiteConfig::current_site_config();
		$config->MobileTheme = 'blackcandymobile';
		$config->write();
		$config->requireDefaultRecords();
		$this->assertTrue(file_exists(TEMP_FOLDER . '/mobile-test-copy-theme/'));
	}

	public function testMobileSiteTypesField() {
		$config = SiteConfig::current_site_config();
		$fields = $config->getCMSFields();
		$typeField = $fields->dataFieldByName('MobileSiteType');
		$this->assertEquals($typeField->getSource(), $config->getMobileSiteTypes());
	}

	public function testMobileDomainGetterAddsProtocolPrefix() {
		$config = SiteConfig::current_site_config();
		$config->MobileDomain = 'mobile.mysite.com';
		$this->assertEquals('http://mobile.mysite.com', $config->MobileDomain);
	}

	public function testFullSiteDomainGetterAddsProtocolPrefix() {
		$config = SiteConfig::current_site_config();
		$config->FullSiteDomain = 'mysite.com';
		$this->assertEquals('http://mysite.com', $config->FullSiteDomain);
	}

	public function tearDown() {
		parent::tearDown();
		exec('rm -rf ' . TEMP_FOLDER . '/mobile-test-copy-theme/');
	}

}