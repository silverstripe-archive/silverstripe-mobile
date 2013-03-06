<?php
/**
 * Extension to the {@link SiteConfig} to add mobile configuration
 * options for the entire site.
 *
 * @package mobile
 */
class MobileSiteConfigExtension extends DataObjectDecorator {

	/**
	 * Append Extra Fields onto the {@link SiteConfig}
	 */
	public function extraStatics() {
		return array(
			'db' => array(
				// Comma-separated list of mobile domains, without protocol
				'MobileDomain' => 'Text',
				// Comma-separated list of non-mobile domains, without protocol
				'FullSiteDomain' => 'Text',
				'MobileTheme' => 'Varchar(255)',
				'MobileSiteType' => 'Enum("Disabled,RedirectToDomain,MobileThemeOnly","Disabled")',
			),
			'defaults' => array(
				'MobileDomain' => 'http://m.' . $_SERVER['HTTP_HOST'],
				'FullSiteDomain' => 'http://' . $_SERVER['HTTP_HOST'],
				'MobileTheme' => 'blackcandymobile',
				'MobileSiteType' => 'Disabled'
			)
		);
	}
	

	/**
	 * @return String The first available domain, with the current protocol prefixed,
	 * suitable for redirections etc.
	 */
	public function getMobileDomainNormalized() {
		$domains = explode(',', $this->owner->MobileDomain);
		$domain = array_shift($domains);
		if(!parse_url($domain, PHP_URL_SCHEME)) $domain = Director::protocol() . $domain;
		return $domain;
	}

	/**
	 * @return String The first available domain, with the current protocol prefixed,
	 * suitable for redirections etc.
	 */
	public function getFullSiteDomainNormalized() {
		$domains = explode(',', $this->owner->FullSiteDomain);
		$domain = array_shift($domains);
		if(!parse_url($domain, PHP_URL_SCHEME)) $domain = Director::protocol() . $domain;
		return $domain;
	}

	/**
	 * Provide a default if MobileSiteType is not set.
	 * @return string
	 */
	public function getMobileSiteType() {
		$defaults = $this->owner->stat('defaults');
		$value = $this->owner->getField('MobileSiteType');
		if(!$value) $value = $defaults['MobileSiteType'];
		return $value;
	}

	/**
	 * Return possible values for the MobileSiteType field mapped
	 * to a human readable title.
	 * @return array
	 */
	public function getMobileSiteTypes() {
		$types = array();
		$types['Disabled'] = _t('MobileSiteConfig.DISABLED', 'Disabled');
		$types['RedirectToDomain'] = _t('MobileSiteConfig.REDIRECTDOMAIN', 'Mobile users are redirected to mobile domain');
		$types['MobileThemeOnly'] = _t('MobileSiteConfig.USEANOTHERTHEME', 'Mobile users see mobile theme, but no redirection occurs');
		return $types;
	}

	/**
	 * Append extra fields to the new Mobile tab in the cms.
	 */
	public function updateCMSFields(FieldSet $fields) {
		$fields->addFieldsToTab(
			'Root.Mobile',
			array(
				new OptionsetField('MobileSiteType', _t('MobileSiteConfig.MOBILESITEBEHAVIOUR', 'Mobile site behaviour'), $this->getMobileSiteTypes()),
				new TextField('MobileDomain', _t('MobileSiteConfig.MOBILEDOMAIN', 'Mobile domain <small>(e.g. m.mysite.com, needs to be different from "Full site domain")</small>')),
				new TextField('FullSiteDomain', _t('MobileSiteConfig.FULLSITEDOMAIN', 'Full site domain <small>(e.g. mysite.com, usually doesn\'t need to be changed)</small>')),
				new DropdownField('MobileTheme', _t('MobileSiteConfig.MOBILETHEME', 'Mobile theme'), $this->owner->getAvailableThemes(), '', null, _t('SiteConfig.DEFAULTTHEME', '(Use default theme)'))
			)
		);
	}

}

/**
 * Copies a directory from source to destination
 * completely by recursively copying each
 * individual file.
 * 
 * Note: This will ignore ".svn" directories.
 * 
 * @param string $src Source path
 * @param string $dst Destination path
 */
function rcopy($src, $dst) {
	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ($file = readdir($dir))) {
		if(($file != '.') && ($file != '..') && ($file != '.svn')) {
			if(is_dir($src . '/' . $file)) {
				rcopy($src . '/' . $file, $dst . '/' . $file);
			} else {
				copy($src . '/' . $file, $dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}
