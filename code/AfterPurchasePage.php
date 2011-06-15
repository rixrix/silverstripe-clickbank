<?php
/**
 * A special page required by ClickBank also called 'Thank You' page 
 * 
 * @package		silverstripe-clickbank
 * @subpackage	code
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class AfterPurchasePage extends Page {
	static $db = array (
		'validateClickBankRequest' => 'Boolean',
		'loginAfterClickBankRequestIsValid' => 'Boolean'
	);
	
	static $allowed_children = array (
		'AfterPurchasePage'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Content.Main', new LabelField('ReplacementTextHeader', 'Replacements: $CBReceipt = ClickBank Receipt, $CBName = ClickBank Member Name'));
		$fields->addFieldToTab('Root.Content.Main', new CheckboxField('validateClickBankRequest', 'Validate ClickBank Request ?'));
		$fields->addFieldToTab('Root.Content.Main', new CheckboxField('loginAfterClickBankRequestIsValid', 'Login member after ClickBank Validation ?'));
		$fields->addFieldToTab('Root.Content.Main', new LabelField('loginAfterClickBankRequestIsValidHelp', "NOTE: You need to tick 'Validate ClickBank Request' if you want to login the user after validation"));

		return $fields;
	}
}

class AfterPurchasePage_Controller extends Page_Controller {
	static $url_handlers = array (
		'' => 'afterPurchase'
	);	
	
	/**
	 * Default action handler for this page
	 * 
	 * @param	SS_HTTPRequest	$request
	 * @return	Object			AfterPurchasePage
	 */
	public function afterPurchase(SS_HTTPRequest $request) {
		if ($request->isGET()) {
			if ($this->validateClickBankRequest) {
				$cbreceipt = $request->getVar('cbreceipt');
				$cbpop = $request->getVar('cbpop');
				$name = $request->getVar('cname');
				$email = $request->getVar('cemail');
				
				if (!empty($cbreceipt) && !empty($cbpop)) {
					if (ClickBank::validate_afterpurchase_request($request->getVars())) {
						
						// make the member status to logged-in
						$member = DataObject::get_one('Member', "Email = '{$email}'");
						if ($member) {
							$member->logIn();
						}
						
						// few handy replacement texts 
						$content = $this->Content;
						$content = str_replace('$CBReceipt', $cbreceipt, $content);
						$content = str_replace('$CBName', $name, $content);
						
						$data = array (
							'Title' => $this->Title,
							'Content' => $content
						);
						
						return $this->customise($data)->renderWith(array('AfterPurchasePage' => 'Page'));
					}
				}
			} else {
				$data = array (
					'Title' => $this->Title,
					'Content' => $this->Content
				);
				return $this->customise($data)->renderWith(array('AfterPurchasePage' => 'Page'));
			}
		}
		
		return $this->redirect('/server-error');
	}
}