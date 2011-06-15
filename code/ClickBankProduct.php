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
		$field->addFieldToTab('Root.Content.Main', new LiteralField('CBSettings', '<h2>ClickBank Details</h2>'));
		$field->addFieldToTab('Root.Content.Main', new CheckboxField('RedirectToClickBank', 'Redirect To ClickBank'));
		$field->addFieldToTab('Root.Content.Main', new TextField('ProductID', 'ClickBank Product ID'));
		
		$cb_product_url = '';
		$cb_product_alias = '';
		if (!empty($this->ProductID)) {
			$cb_product_url = $this->getClickBankURI();
			$cb_product_alias = Director::absoluteURL($this->Link());	
		}
		
		$field->addFieldToTab('Root.Content.Main', new ReadonlyField('ClickBankURIAlias', 'Product URL Alias', $cb_product_alias));
		$field->addFieldToTab('Root.Content.Main', new ReadonlyField('ClickBankURI', 'ClickBank Product URL', $cb_product_url));
		
		return $field;
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
			
			return $this->customise($data)->renderWith(array('ClickBankProductPage' => 'Page')); 
		}	
	}
}