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
				'ClickBankSkin' => 'Varchar(10)',
				'ClickBankTestMode' => 'Boolean'
			)
		);
	}
	
	/**
	 * Add config options in CMS site configuration section
	 * 
	 * @see DataObjectDecorator::updateCMSFields()
	 */
	function updateCMSFields(&$fields) {
		// Show warning message		
		if (!ClickBankManager::validate_required_modules()) {
			$fields->addFieldToTab("Root.ClickBank", new LiteralField("ClickBankWarningHeader", _t('ClickBank.MESSAGE_WARNINGMISSINGMODULE')));
		}
						
		$fields->addFieldToTab("Root.ClickBank", new LiteralField('ClickBankIPNTitle', _t('ClickBank.CONFIG_GENERAL_SETTINGS_SECTION_TITLE')));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankID', _t('ClickBank.CONFIG_CLICKBANKID')));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankSecretKey', _t('ClickBank.CONFIG_SECRETKEY')));
		$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankSkin', _t('ClickBank.CONFIG_SKINID')));
		
		$clickBankHolder = DataObject::get_one('ClickBankHolder');
		$site_ipn_url = '';
		if ($clickBankHolder) {
			$site_ipn_url = Director::absoluteURL('/clickbank/ipn');
		}
		$ipn_textfield = new ReadonlyField('ClickBankIpnUrl', _t('ClickBank.CONFIG_IPN_URL'), $site_ipn_url);
		$fields->addFieldToTab("Root.ClickBank", $ipn_textfield);
		$fields->addFieldToTab("Root.ClickBank", new LabelField('IpnUrlLabel', _t('ClickBank.CONFIG_IPN_URL_LABEL')));
		
		//$fields->addFieldToTab("Root.ClickBank", new LiteralField('ClickBankAPI', _t('ClickBank.CONFIG_API_SECTION_TITLE')));
		//$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankApiKey', _t('ClickBank.CONFIG_API_KEY')));
		//$fields->addFieldToTab("Root.ClickBank", new TextField('ClickBankDeveloperApiKey', _t('ClickBank.CONFIG_API_DEVELOPER_KEY')));
		
		//$fields->addFieldToTab("Root.ClickBank", new LiteralField('ClickBankSiteModeHeader', _t('ClickBank.CONFIG_SITE_MODE_SECTION_TITLE')));
		//$fields->addFieldToTab("Root.ClickBank", new CheckboxField('ClickBankTestMode', _t('ClickBank.CONFIG_SITE_MODE')));
	}
}