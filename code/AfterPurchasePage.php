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
		$fields->addFieldToTab('Root.Content.Main', new LabelField('ReplacementTextLabel', _t('ClickBank.APP_REPLACEMENT_LABEL')));
		$fields->addFieldToTab('Root.Content.Main', new CheckboxField('validateClickBankRequest', _t('ClickBank.APP_VALIDATE_REQUEST')));
		$fields->addFieldToTab('Root.Content.Main', new CheckboxField('loginAfterClickBankRequestIsValid', _t('ClickBank.APP_LOGIN_AFTER_VALIDATION')));
		$fields->addFieldToTab('Root.Content.Main', new LabelField('loginAfterClickBankRequestIsValidHelpLabel', _t('ClickBank.APP_LOGIN_AFTER_VALIDATION_LABEL')));

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
						
						$member = DataObject::get_one('Member', "Email = '{$email}'");
						
						// make the member status to logged-in
						if ($member && $this->loginAfterClickBankRequestIsValid) {
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