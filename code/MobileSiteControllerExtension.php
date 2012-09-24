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

		if (Director::redirected_to()) {
			return;
		}

		self::$is_mobile = false;
    		$config = SiteConfig::current_site_config(); 
    		$request = $this->owner->getRequest();

    		$this->set_fullsite_cookie($request); 
    		$state = $this->siteState($config->MobileSiteType, $this->isFullSite($request)); 

		switch ($state) {
	    		case 1:
				$this->switchToTheMobiletheme($config->MobileTheme); 
		      		break;
		    	case 2: 
				return $this->owner->redirect($config->MobileDomain, 302);
		      		break;
		}
	}

	public function switchToTheMobiletheme($theme) {
  	  	SSViewer::set_theme($theme);
    		self::$is_mobile = true;
	}

	/**
	 * What to do next?
	 */
	public function siteState($MobileSiteType, $fullSite) {
		$config = SiteConfig::current_site_config(); 
		if($this->onMobileDomain())
	      		return 1;
		if($fullSite)
 			return 0;
	    	if(MobileBrowserDetector::is_mobile() && $MobileSiteType == 'RedirectToDomain')    
			return 2; 
	    	if(MobileBrowserDetector::is_mobile())
	    		return 1;
	    	return 0; 
	}
	
	/**
	 * Update the fullSite cookie for 30 minutes
	 */
	public function isFullSite($request) {
		$fsVal = (is_numeric($request->getVar('fullSite')))?  $request->getVar('fullSite'):Cookie::get('fullSite');
    		return is_numeric($fsVal) && $fsVal; 
	}

	/**
	 * Update the fullSite cookie for 30 minutes
	 */
	static public function set_fullsite_cookie($request) {
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
		$domains = explode(',', $config->MobileDomain);
		foreach($domains as $domain) {
			if(!parse_url($domain, PHP_URL_SCHEME)) $domain = Director::protocol() . $domain; // Normalize URL
			$parts = parse_url($domain);
			$compare = @$parts['host'];
			if(@$parts['port']) $compare .= ':' . $parts['port'];
			if($compare && $compare == $_SERVER['HTTP_HOST']) return true;
		}

		return false;
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
