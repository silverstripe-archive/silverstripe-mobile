<?php

class MobileDeviceTheme extends DataObject {

	static $db = array(
		'Device' => 'Varchar',
		'Theme' => 'Varchar'
	); 
	
	static $has_one = array(
		'Configuration' => 'SiteConfig'
	);    
	
	
	/**
	 * 
	 * @return FieldSet
	 */
	function getPopupFields(){       
		
		$siteConfig = SiteConfig::current_site_config(); 
		
		return new FieldSet(          
			new DropdownField('Device', _t('MobileSiteConfig.MOBILETHEME', 'Mobile device'), array(
				'android' => 'android',
				'iphone' => 'iphone',
				'ipad' => 'ipad',
				'blackberry' => 'blackberry',
				'palm' => 'palm'
			)),
			new DropdownField('Theme', _t('MobileSiteConfig.MOBILETHEME', 'Mobile theme'), $siteConfig->getAvailableThemes())
		);
	}
	     
	/**
	 * 
	 * @return void
	 */
	function onBeforeWrite(){
		parent::onBeforeWrite();
		$siteConfig = SiteConfig::current_site_config();
		$this->ConfigurationID = $siteConfig->ID;
	}
	
	
}