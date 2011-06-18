<?php
/**
 * An extension for memberprofiles module for ClickBank members
 * 
 * @package		silverstripe-clickbank
 * @subpackage	extensions
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankMemberProfileExtension extends DataObjectDecorator {
	public function extraStatics() {
		return array (
			'db' => array (
				'ClickBankAccountType' => "Enum('None, Paid, Free')"
			),
			'summary_fields' => array (
				'ClickBankAccountType'
			),
			'has_one' => array (
				'ClickBankProfile' => 'ClickBankMemberProfile'
			),
			'has_many' => array (
				'ClickBankIpnLogs' => 'ClickBankIpnLog'
			)
		);
	}
	
	public function populateDefaults() {
		$this->owner->ClickBankAccountType = 'None';
	}
	
	public function updateMemberFormFields($fields) {
		$fields->removeByName('ClickBankAccountType');
	}
	
	public function updateCMSFields($fields) {
		$clickBankProfile = $this->owner->ClickBankProfile();
		
		$fields->removeFieldFromTab('Root.Main', 'ClickBankProfileID');
		$fields->addFieldsToTab(
			'Root.ClickBankProfile',
			array (
				new HeaderField('ClickBankAccountTypeHeader', _t('ClickBank.PROFILE_ACCOUNT_TYPE_SECTION_TITLE')),
				new DropdownField('ClickBankAccountType', _t('ClickBank.PROFILE_ACCOUNT_TYPE'), $this->owner->dbObject('ClickBankAccountType')->enumValues()),
				
				new HeaderField('ClickBankProfileHeader', _t('ClickBank.PROFILE_DETAILS_SECTION_TITLE')),
				new TextField('ccustaddr1', _t('ClickBank.PROFILE_ADDRESS_1'), $clickBankProfile->ccustaddr1),
				new TextField('ccustaddr2', _t('ClickBank.PROFILE_ADDRESS_2'), $clickBankProfile->ccustaddr2),
				new TextField('ccustcity', _t('ClickBank.PROFILE_CITY'), $clickBankProfile->ccustcity),
				new TextField('ccuststate', _t('ClickBank.PROFILE_STATE'), $clickBankProfile->ccuststate),
				new TextField('ccustcounty', _t('ClickBank.PROFILE_ZIP'), $clickBankProfile->ccustzip),
				new TextField('ccustzip', _t('ClickBank.PROFILE_COUNTY'), $clickBankProfile->ccustcounty),
				new TextField('ccustcc', _t('ClickBank.PROFILE_COUNTRY'), $clickBankProfile->ccustcc),
				new TextField('ccustshippingstate', _t('ClickBank.PROFILE_SHIPPING_STATE'), $clickBankProfile->ccustshippingstate),
				new TextField('ccustshippingzip', _t('ClickBank.PROFILE_SHIPPING_ZIP'), $clickBankProfile->ccustshippingzip),
				new TextField('ccustshippingcountry', _t('ClickBank.PROFILE_SHIPPING_COUNTRY'), $clickBankProfile->ccustshippingcountry),
				
			)
		);
	}
}