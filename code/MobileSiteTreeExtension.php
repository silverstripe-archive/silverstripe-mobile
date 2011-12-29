<?php
/**
 * @package mobile
 */
class MobileSiteTreeExtension extends DataObjectDecorator {
	
	function MetaTags(&$tags) {
		$config = SiteConfig::current_site_config();

		// Ensure a canonical link is placed, for semantic correctness and SEO
		if(Controller::has_curr() && Controller::curr()->hasMethod("onMobileDomain") && Controller::curr()->onMobileDomain() && $config->MobileSiteType == 'RedirectToDomain') {
			$oldBaseURL = Director::baseURL();
			Director::setbaseURL($config->FullSiteDomain);
			$tags .= sprintf('<link rel="canonical" href="%s" />', $this->owner->AbsoluteLink()) . "\n";
			Director::setbaseURL($oldBaseURL);
		}
	}
	
}