<?php
/**
 * Extension to the {@link SiteConfig} to add mobile configuration
 * options for the entire site.
 *
 * @package mobile
 */
class MobileSiteConfigExtension extends DataObjectDecorator {

	/**
	 * The path the default mobile theme should be copied
	 * to when {@link SiteConfig} is first created in the database.
	 * 
	 * @see MobileSiteConfigExtension::requireDefaultRecords()
	 * @var string
	 */
	protected static $theme_copy_path;

	public static function set_theme_copy_path($path) {
		self::$theme_copy_path = $path;
	}

	public static function get_theme_copy_path() {
		if(!self::$theme_copy_path) {
			return '../' . THEMES_DIR . '/blackcandymobile';
		} else {
			return self::$theme_copy_path;
		}
	}

	/**
	 * Append Extra Fields onto the {@link SiteConfig}
	 */
	public function extraStatics() {
		return array(
			'db' => array(
				'MobileDomain' => 'Varchar(50)',
				'FullSiteDomain' => 'Varchar(50)',
				'MobileTheme' => 'Varchar(255)',
				'MobileSiteType' => 'Varchar(255)'
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
	 * Check whether the protocol was specified for the mobile domain,
	 * and if it wasn't, then assume http. e.g. "mysite.com" => "http://mysite.com"
	 * 
	 * @return string
	 */
	public function getMobileDomain() {
		$defaults = $this->owner->stat('defaults');

		$value = $this->owner->getField('MobileDomain');
		if(!$value) $value = $defaults['MobileDomain'];

		if(strpos($value, '://') === false) {
			return 'http://' . $value;
		} else {
			return $value;
		}
	}

	/**
	 * Check whether the protocol was specified for the full site domain,
	 * and if it wasn't, then assume http. e.g. "mysite.com" => "http://mysite.com"
	 * 
	 * @return string
	 */
	public function getFullSiteDomain() {
		$defaults = $this->owner->stat('defaults');

		$value = $this->owner->getField('FullSiteDomain');
		if(!$value) $value = $defaults['FullSiteDomain'];

		if(strpos($value, '://') === false) {
			return 'http://' . $value;
		} else {
			return $value;
		}
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
	 * The default theme is "blackcandymobile". If this is still set
	 * as a field on SiteConfig, then make sure that it's copied
	 * into the themes directory from the mobile module.
	 */
	public function requireDefaultRecords() {
		if($this->owner->getField('MobileTheme') == 'blackcandymobile') {
			$this->copyDefaultTheme();
		}
	}

	public static function copyDefaultTheme() {
		$src = '../' . MOBILE_DIR . '/blackcandymobile';
		$dst = self::get_theme_copy_path();

		if(!file_exists($dst)) {
			@mkdir($dst);
			if(is_writable($dst)) {
				rcopy($src, $dst);
				DB::alteration_message(
					'Default mobile theme "blackcandymobile" has been copied into the themes directory',
					'created'
				);
			} else {
				DB::alteration_message(
					'Could not copy default mobile theme "blackcandymobile" into themes directory (permission denied).
					Please manually copy the "blackcandymobile" directory from the mobile module into the themes directory.',
					'error'
				);
			}
		}
	}

	/**
	 * Append extra fields to the new Mobile tab in the cms.
	 */
	public function updateCMSFields(FieldSet $fields) {
		$fields->addFieldsToTab(
			'Root.Mobile',
			array(
				new OptionsetField('MobileSiteType', _t('MobileSiteConfig.MOBILESITEBEHAVIOUR', 'Mobile site behaviour'), $this->getMobileSiteTypes()),
				new TextField('MobileDomain', _t('MobileSiteConfig.MOBILEDOMAIN', 'Mobile domain')),
				new LiteralField('MobileDomainHelpText', '<label class="helpText">' . _t('MobileSiteConfig.MOBILEDOMAINHELP', '(e.g. "mobile.mysite.com"). Please make sure you point the given domain to this site') . '</label>'),
				new TextField('FullSiteDomain', _t('MobileSiteConfig.FULLSITEDOMAIN', 'Full site domain')),
				new LiteralField('FullSiteDomainHelpText', '<label class="helpText">' . _t('MobileSiteConfig.FULLSITEDOMAINHELP', '(e.g. "mysite.com"). This usually doesn\'t need to be changed') . '</label>'),
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