<?php 

class MobileSiteAccessModifier extends DataObjectDecorator{
	
	function extraStatics(){
		return array(
			"db" => array(
				"CanViewType" => "Enum('Anyone, LoggedInUsers, OnlyTheseUsers, Inherit,iPhoneUsers,iPadUsers,MobileUsers,AndriodUsers,DesktopUsers', 'Inherit')"
			)
		);
	} 
	
	function updateCMSFields($fields){       
		$fields->removeFieldFromTab("Root.Access", "CanViewType");
		$viewersOptionsField = new OptionsetField(
			"CanViewType", 
			""
		);
		$viewersOptionsSource = array();
		$viewersOptionsSource["Inherit"] = _t('SiteTree.INHERIT', "Inherit from parent page");
		$viewersOptionsSource["Anyone"] = _t('SiteTree.ACCESSANYONE', "Anyone");
		$viewersOptionsSource["LoggedInUsers"] = _t('SiteTree.ACCESSLOGGEDIN', "Logged-in users");
		
		$viewersOptionsSource["DesktopUsers"] = _t('SiteTree.ACCESSONLYCOMPUTERS', "Users viewing the site using computers"); 
		$viewersOptionsSource["MobileUsers"] = _t('SiteTree.ACCESSONLYMOBILES', "Users viewing the site using mobile devices"); 
		$viewersOptionsSource["iPhoneUsers"] = _t('SiteTree.ACCESSONLYIPHONES', "Users viewing the site using iPhones");
		$viewersOptionsSource["iPadUsers"] = _t('SiteTree.ACCESSONLYIPADS', "Users viewing the site using iPads");
		$viewersOptionsSource["AndriodUsers"] = _t('SiteTree.ACCESSONLYANDRIOD', "Users viewing the site using Android devices");
		
		
		$viewersOptionsSource["OnlyTheseUsers"] = _t('SiteTree.ACCESSONLYTHESE', "Only these people (choose from list)");
		
		
		
		$viewersOptionsField->setSource($viewersOptionsSource);
		$fields->addFieldToTab("Root.Access", $viewersOptionsField, "WhoCanEditHeader");
	}
	
	function canView($member = null){ 
		$canView = null;
		$siteTree = $this->owner;
		if(0 == strcmp($siteTree->CanViewType, 'DesktopUsers') && !MobileBrowserDetector::is_mobile()){
			$canView = true;
		}
		if(0 == strcmp($siteTree->CanViewType, 'iPhoneUsers') && MobileBrowserDetector::is_iphone()){
			$canView = true;
		}
		if(0 == strcmp($siteTree->CanViewType, 'iPadUsers') && MobileBrowserDetector::is_ipad()){
			$canView = true;
		}
		if(0 == strcmp($siteTree->CanViewType, 'AndriodUsers') && MobileBrowserDetector::is_andriod()){
			$canView = true;
		}
		if(0 == strcmp($siteTree->CanViewType, 'MobileUsers') && MobileBrowserDetector::is_mobile()){
			$canView = true;
		}   
		return $canView;
	} 
	
}