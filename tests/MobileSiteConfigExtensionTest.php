<?php
/**
 * @package mobile
 * @subpackage tests
 */
class MobileSiteConfigExtensionTest extends SapphireTest {

	public function testMobileSiteTypesField() {
		$config = SiteConfig::current_site_config();
		$fields = $config->getCMSFields();
		$typeField = $fields->dataFieldByName('MobileSiteType');
		$this->assertEquals($typeField->getSource(), $config->getMobileSiteTypes());
	}

	public function testMobileDomainGetterAddsProtocolPrefix() {
		$config = SiteConfig::current_site_config();
		$config->MobileDomain = 'mobile.mysite.com';
		$this->assertEquals('http://mobile.mysite.com', $config->MobileDomainNormalized);
	}

	public function testFullSiteDomainGetterAddsProtocolPrefix() {
		$config = SiteConfig::current_site_config();
		$config->FullSiteDomain = 'mysite.com';
		$this->assertEquals('http://mysite.com', $config->FullSiteDomainNormalized);
	}

}