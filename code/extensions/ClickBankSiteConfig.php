<?php
/**
 * An extension to SiteConfig for ClickBank configuration
 * 
 * @package		silverstripe-clickbank
 * @subpackage	extensions
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankSiteConfig extends DataObjectDecorator {
	function extraStatics() {
		return array (
			'db' => array(
				'ClickBankID'  => 'Varchar(255)',
				'ClickBankSecretKey'  => 'Varchar(255)',
				'ClickBankApiKey'  => 'Varchar(255)',
				'ClickBankDeveloperApiKey'  => 'Varchar(255)',
				'ClickBankSkin' => 'Varchar(10)'
			)
		);
	}
	
	/**
	 * Add config options in CMS site configuration section
	 * 
	 * @see DataObjectDecorator::updateCMSFields()
	 */
	function updateCMSFields(&$fields) {
		$fields->addFieldToTab("Root.ClickBank", new LiteralField('ClickBankIPN', '<h2>IPN Settings</h2>'));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankID', 'ClickBank ID'));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankSecretKey', 'Secret Key'));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankSkin', 'ClickBank Skin ID'));
		$clickBankHolder = DataObject::get_one('ClickBankHolder');
		$site_ipn_url = '';
		if ($clickBankHolder) {
			$site_ipn_url = Director::absoluteURL('/clickbank/ipn');
		}
		$ipn_textfield = new ReadonlyField('ClickBankIpnUrl', 'IPN URL', $site_ipn_url);
		$fields->addFieldToTab("Root.ClickBank", $ipn_textfield);
		
		$fields->addFieldToTab("Root.ClickBank", new LiteralField('ClickBankAPI', '<h2>API Settings</h2>'));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankApiKey', 'API Key'));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankDeveloperApiKey', 'Developer API Key'));
	}
}