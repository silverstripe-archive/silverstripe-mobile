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
	 * Stores state information as to which site is currently served.
	 */
	private static $is_mobile = false;

	/**
	 * Override the default behavior to ensure that if this is a mobile device
	 * or if they are on the configured mobile domain then they receive the mobile site.
	 */
	public function onAfterInit() {
		self::$is_mobile = false;
		$config = SiteConfig::current_site_config();
		$request = $this->owner->getRequest();
		
		// If we've accessed the homepage as /home/, then we redirect to / and don't want to double redirect here
		if (Director::redirected_to()) {
			return;
		}

		// Enforce the site (cookie expires in 30 minutes)
		$fullSite = $request->getVar('fullSite');
		if(is_numeric($fullSite)) {
			Cookie::set('fullSite', (int)$fullSite);

			// use the host of the desktop version of the site to set cross-(sub)domain cookie
			if (!empty($config->FullSiteDomain)) {
				$parsedURL = parse_url($config->FullSiteDomain);
				if(!headers_sent($file, $line)) {
					setcookie('fullSite', $fullSite, time() + self::$cookie_expire_time, null, '.' . $parsedURL['host']);
				} else {
					user_error(sprintf('Cookie \'fullSite\' can\'t be set. Output started at line %s in %s', $line, $file));
				}
			} else { // otherwise just use a normal cookie with the default domain
				if(!headers_sent($file, $line)) {
					setcookie('fullSite', $fullSite, time() + self::$cookie_expire_time);
				} else {
					user_error(sprintf('Cookie \'fullSite\' can\'t be set. Output started at line %s in %s', $line, $file));
				}
			}
		}

		// Site is being forced via flag or cookie
		$fullSiteCookie = Cookie::get('fullSite');
		if(is_numeric($fullSiteCookie)) {
			// Full site requested
			if($fullSiteCookie) {
				if($this->onMobileDomain() && $config->MobileSiteType == 'RedirectToDomain') {
					return $this->owner->redirect($config->FullSiteDomain, 301);
				}

				return;
			}
			// Mobile site requested
			else {
				if(!$this->onMobileDomain() && $config->MobileSiteType == 'RedirectToDomain') {
					return $this->owner->redirect($config->MobileDomain, 301);
				}

				SSViewer::set_theme($config->MobileTheme);
				self::$is_mobile = true;
				return;
			}
		}

		// If the user requested the mobile domain, set the right theme
		if($this->onMobileDomain()) {
			SSViewer::set_theme($config->MobileTheme);
			self::$is_mobile = true;
		}

		// User just wants to see a theme, but no redirect occurs
		if(MobileBrowserDetector::is_mobile() && $config->MobileSiteType == 'MobileThemeOnly') {
			SSViewer::set_theme($config->MobileTheme);
			self::$is_mobile = true;
		}

		// If on a mobile device, but not on the mobile domain and has been setup for redirection
		if(!$this->onMobileDomain() && MobileBrowserDetector::is_mobile() && $config->MobileSiteType == 'RedirectToDomain') {
			return $this->owner->redirect($config->MobileDomain, 301);
		}
	}

	/**
	 * Provide state information. We can't always rely on current theme, 
	 * as the user may elect to use the same theme for both sites.
	 *
	 * Useful for example for template conditionals.
	 */
	static public function is_mobile() {
		return self::$is_mobile;
	}

	/**
	 * Return whether the user is on the mobile version of the website.
	 * Caution: This only has an effect when "MobileSiteType" is configured as "RedirectToDomain".
	 * 
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
	 * @return boolean
	 */
	public function isMobile() {
		return MobileSiteControllerExtension::$is_mobile;
	}

	/**
	 * Return a link to the full site.
	 * 
	 * @return string
	 */
	public function FullSiteLink() {
		return Controller::join_links($this->owner->Link(), '?fullSite=1');
	}
	
	/**
	 * @return string
	 */
	public function MobileSiteLink() {
		return Controller::join_links($this->owner->Link(), '?fullSite=0');
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
