<?php
/**
 * An extension memberprofiles module for ClickBank members
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
				new HeaderField('ClickBankAccountTypeHeader', 'Account Type'),
				new DropdownField('ClickBankAccountType', 'ClickBank Account Type', $this->owner->dbObject('ClickBankAccountType')->enumValues()),
				
				new HeaderField('ClickBankProfileHeader', 'Profile Details'),
				new TextField('ccustaddr1', 'Address 1', $clickBankProfile->ccustaddr1),
				new TextField('ccustaddr2', 'Address 2', $clickBankProfile->ccustaddr2),
				new TextField('ccustcity', 'City', $clickBankProfile->ccustcity),
				new TextField('ccustcounty', 'Zip', $clickBankProfile->ccustzip),
				new TextField('ccustzip', 'County', $clickBankProfile->ccustcounty),
				new TextField('ccustcc', 'Country', $clickBankProfile->ccustcc),
				new TextField('ccustshippingstate', 'Shipping State', $clickBankProfile->ccustshippingstate),
				new TextField('ccustshippingzip', 'Shipping Zip Code', $clickBankProfile->ccustshippingzip),
				new TextField('ccustshippingcountry', 'Shipping Country', $clickBankProfile->ccustshippingcountry),
				new TextField('ccuststate', 'State', $clickBankProfile->ccuststate),
				new TextField('ccuststate', 'State', $clickBankProfile->ccuststate),
			)
		);
	}
}