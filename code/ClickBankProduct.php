<?php
/**
 * ClickBank product page
 * 
 * @package		silverstripe-clickbank
 * @subpackage	code
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankProduct extends Page {
	static $db = array (
		'ProductID' => 'Varchar(10)',
		'RedirectToClickBank' => "Boolean"
	);
	
	static $has_one = array (
		'ProductImage' => 'Image'
	);
	
	public static $defaults = array (
		'RedirectToClickBank' => true,
	);
	
	static $default_parent = 'ClickBankHolder';
	
	static $allowed_children = array();
	
	static $can_be_root = false;
	
	/**
	 * Getter for ClickBankLink property
	 * 
	 * @param	none
	 * @return	string	
	 */
	protected function getClickBankURI() {
		$clickBankSiteConfig = SiteConfig::current_site_config();
		if (!empty($clickBankSiteConfig->ClickBankID)) {
			$cbskin = ($clickBankSiteConfig->ClickBankSkin) ? "/?cbskin={$clickBankSiteConfig->ClickBankSkin}" : "";
			return "http://{$this->ProductID}.{$clickBankSiteConfig->ClickBankID}.pay.clickbank.net" . $cbskin;
		}
		return false;
	}
	
	public function getCMSFields() {
		$field = parent::getCMSFields();
		$field->addFieldToTab('Root.Content.Main', new LiteralField('CBSettings', _t('ClickBank.PP_SETTINGS_SECTION_TITLE')));
		$field->addFieldToTab('Root.Content.Main', new CheckboxField('RedirectToClickBank', _t('ClickBank.PP_REDIRECT_TO_CLICKBANK')));
		$field->addFieldToTab('Root.Content.Main', new TextField('ProductID', _t('ClickBank.PP_CLICKBANK_PRODUCT_ID')));
		
		$cb_product_url = '';
		$cb_product_alias = '';
		if (!empty($this->ProductID)) {
			$cb_product_url = $this->getClickBankURI();
			$cb_product_alias = Director::absoluteURL($this->Link());	
		}
		
		$field->addFieldToTab('Root.Content.Main', new ReadonlyField('ClickBankURIAlias', _t('ClickBank.PP_PRODUCT_URL_ALIAS'), $cb_product_alias));
		$field->addFieldToTab('Root.Content.Main', new ReadonlyField('ClickBankURI', _t('ClickBank.PP_CLICKBANK_PRODUCT_URL_ALIAS'), $cb_product_url));
		
		// Product Image
		$field->addFieldToTab('Root.Content.ProductImage', new ImageField('ProductImage'));
		
		
		return $field;
	}
	
	/**
	 * Flags required fields in the CMS admin
	 * 
	 * @param	none
	 * @return	Object	Error message
	 */
	public function getCMSValidator() {
		return new RequiredFields('ProductID');
	}
}

class ClickBankProduct_Controller extends Page_Controller {
	static $allowed_actions = array(
		'index'
	);
	
	/**
	 * Default function
	 * 
	 * @param	none
	 * @return	none	Displays product page or redirect to ClickBank payment page
	 */
	public function index() {
		if($this->RedirectToClickBank) {
			return Director::redirect($this->getClickBankURI());
		} else {
			$data = array (
				'Title' => $this->Title,
				'Content' => $this->Content
			);
			
			return $this->customise($data)->renderWith(array('ClickBankProductPage', 'Page')); 
		}	
	}
}