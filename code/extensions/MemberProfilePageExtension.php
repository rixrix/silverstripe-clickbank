<?php
/**
 * An extension to memberprofiles module. This will a new tab in memberprofile content
 * tab 
 *   
 * @package		silverstripe-clickbank
 * @subpackage	extensions
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class MemberProfilePageExtension extends DataObjectDecorator {
	public function extraStatics() {
		return array (
			'db' => array (
				'ClickBankPageTitle'   => 'Varchar(255)',
				'ClickBankPageContent' => 'HTMLText',
				'ClickBankEnableProfile' => 'Boolean'
			)
		);
	}
	
	/**
	 * Displays new ClickBank tab in MemberProfile CMS field
	 * 
	 * @return	Object	ClickBank Tab
	 */
	public function updateCMSFields($fields) {
		$fields->addFieldToTab('Root.Content.ClickBank', new TextField('ClickBankPageTitle', 'Title'));
		$fields->addFieldToTab('Root.Content.ClickBank', new HtmlEditorField('ClickBankPageContent', 'Content'));
		$fields->addFieldToTab('Root.Content.ClickBank', new LabelField('ReplacementTextHeader', 'Replacements: $CBReceipt = Last ClickBank transaction receipt'));
		$fields->addFieldToTab('Root.Content.ClickBank', new CheckboxField('ClickBankEnableProfile', 'Enable ClickBank Profile Page'));
		
		return $fields;
	}
}

/**
 * MemberProfilePage_Controller extension
 */
class MemberProfilePageExtension_Controller extends Extension {
	public static $allowed_actions = array (
		'download',
		'clickbankProfile',
		'ClickBankProfileForm'
	);
	
	/**
	 * Display ClickBank profile.
	 * 
	 * @see		MemberProfilePage_Controller::indexProfile()
	 * @param	none
	 * @return	mixed
	 */
	public function clickbankProfile() {
		if (!$this->owner->AllowProfileEditing) {
			return Security::permissionFailure($this->owner, _t(
				'MemberProfiles.CANNOTEDIT',
				'You cannot edit your profile via this page.'
			));
		}
		
		$member = Member::currentUser();
		
		foreach($this->owner->Groups() as $group) {
			if(!$member->inGroup($group)) {
				return Security::permissionFailure($this->owner);
			}
		}
		
		$data = array (
			'Title' => $this->owner->obj('ClickBankPageTitle'),
			'Content' => $this->owner->obj('ClickBankPageContent')
		);
		
		return $this->owner->customise($data)->renderWith(array('ClickBankMemberProfilePage', 'Page'));
	}
	
	/**
	 * Displays ClickBank profile
	 * 
	 * @todo	Temp. readonly mode
	 * @param	none
	 * @return	Object	Form
	 */
	public function ClickBankProfileForm() {
		$member = Member::currentUser();
		$clickBankProfile = $member->ClickBankProfile(); 
		
		// address
		$ccustaddr1 = new ReadonlyField('ccustaddr1', 'Address 1', $clickBankProfile->ccustaddr1);
		$ccustaddr2 = new ReadonlyField('ccustaddr2', 'Address 2', $clickBankProfile->ccustaddr2);
		$ccustcity = new ReadonlyField('ccustcity', 'City', $clickBankProfile->ccustcity);
		$ccustcounty = new ReadonlyField('ccustcounty', 'County', $clickBankProfile->ccustcounty);
		$ccuststate = new ReadonlyField('ccuststate', 'State', $clickBankProfile->ccuststate);
		$ccustzip = new ReadonlyField('ccustzip', 'Zip Code', $clickBankProfile->ccustzip);
		$ccustcc = new ReadonlyField('ccustcc', 'Country', $clickBankProfile->ccustcc);
		 
		// shipping address
		$ccustshippingstate = new ReadonlyField('ccustshippingstate', 'Shipping State', $clickBankProfile->ccustshippingstate);
		$ccustshippingzip = new ReadonlyField('ccustshippingzip', 'Shipping Zip Code', $clickBankProfile->ccustshippingzip);
		$ccustshippingcountry = new ReadonlyField('ccustshippingcountry', 'Shipping Country', $clickBankProfile->ccustshippingcountry);
		
		$fields = new FieldSet(
			new HeaderField('AddressHeader', 'Address'),
			$ccuststate,
			$ccustzip,
			$ccustcc,
			
			new HeaderField('ShippingAddressHeader', 'Shipping Address'),
			$ccustshippingstate,
			$ccustshippingzip,
			$ccustshippingcountry,
			
			new LiteralField('', '<br />')
		);
		
		$actions = new FieldSet(
			new FormAction('doClickBankProfileForm', 'Return Main')
		);
		
		return new Form($this->owner, 'ClickBankProfileForm', $fields, $actions);
	}
	
	/**
	 * Updates an existing ClickBank Member's profile.
	 *
	 * @todo	temp. redirect to main member's profile page.
	 */
	public function doClickBankProfileForm() {
		return Director::redirect($this->owner->Link());
	}
}