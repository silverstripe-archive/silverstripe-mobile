<?php
/**
 * Extension to {@link ContentController} which handles
 * redirection from main site to mobile.
 * 
 * @package mobile
 */
class MobileSiteControllerExtension extends Extension {

	/**
	 * The expiration time of a cookie set for full site requests
	 * from the mobile site. Default is 30 minutes (1800 seconds)
	 * @var int
	 */
	public static $cookie_expire_time = 1800;

	/**
	 * Override the default behavior to ensure that if this is a mobile device
	 * or if they are on the configured mobile domain then they receive the mobile site.
	 */
	public function onBeforeInit() {
		$config = SiteConfig::current_site_config();

		// Redirect users to the full site if requested (cookie expires in 30 minutes)
		if(isset($_GET['fullSite'])) {
			$_COOKIE['fullSite'] = 1;
			setcookie('fullSite', 1, time() + self::$cookie_expire_time);
		}

		// Redirect to the full site if user requested
		if($this->onMobileDomain() && !empty($_COOKIE['fullSite']) && $config->MobileSiteType == 'RedirectToDomain') {
			return $this->owner->redirect($config->FullSiteDomain);
		} elseif(!empty($_COOKIE['fullSite'])) {
			return; // nothing more to be done
		}

		// If the user requested the mobile domain, set the right theme
		if($this->onMobileDomain()) {
			SSViewer::set_theme($config->MobileTheme);
		}

		// User just wants to see a theme, but no redirect occurs
		if(MobileBrowserDetector::is_mobile() && $config->MobileSiteType == 'MobileThemeOnly') {
			SSViewer::set_theme($config->MobileTheme);
		}

		// If on a mobile device, but not on the mobile domain and has been setup for redirection
		if(!$this->onMobileDomain() && MobileBrowserDetector::is_mobile() && $config->MobileSiteType == 'RedirectToDomain') {
			return $this->owner->redirect($config->MobileDomain);
		}
	}

	/**
	 * Return whether the user is on the mobile version of the website
	 * @return boolean
	 */
	public function onMobileDomain() {
		$config = SiteConfig::current_site_config();
		$parts = parse_url($config->MobileDomain);
		if(isset($parts['host']) && $parts['host'] == $_SERVER['HTTP_HOST']) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return a link to the full site.
	 * @return string
	 */
	public function FullSiteLink() {
		return $this->owner->Link() . '?fullSite=1';
	}

	/**
	 * Is the current HTTP_USER_AGENT a known iPhone or iPod Touch
	 * mobile agent string?
	 * 
	 * @return boolean
	 */
	public function IsiPhone() {
		return MobileBrowserDetector::is_iphone();
	}

	/**
	 * Is the current HTTP_USER_AGENT a known Android mobile
	 * agent string?
	 * 
	 * @return boolean
	 */
	public function IsAndroid() {
		return MobileBrowserDetector::is_android();
	}

	/**
	 * Is the current HTTP_USER_AGENT a known Opera Mini
	 * agent string?
	 * 
	 * @return boolean
	 */
	public function IsOperaMini() {
		return MobileBrowserDetector::is_opera_mini();
	}

	/**
	 * Is the current HTTP_USER_AGENT a known Blackberry
	 * mobile agent string?
	 * 
	 * @return boolean
	 */
	public function IsBlackBerry() {
		return MobileBrowserDetector::is_blackberry();
	}

}
