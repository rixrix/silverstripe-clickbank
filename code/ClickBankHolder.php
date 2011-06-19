<?php
/**
 * ClickBank product page holder 
 * 
 * @package		silverstripe-clickbank
 * @subpackage	code
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankHolder extends Page {
	static $db = array (
		'ItemsPerPage' => 'Int'
	);
	
	static $defaults = array(
		'ItemsPerPage' => 10
	);
	
	static $allowed_children = array (
		'ClickBankProduct'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Content.Main', new TextField('ItemsPerPage', 'Items Per Page'));
		
		return $fields;
	}
}

class ClickBankHolder_Controller extends Page_Controller {
	
	/**
	 * Displays product list
	 * 
	 * @param	none
	 * @return	Object	result set
	 */
	public function ClickBankProducts() {
		if(!isset($_GET['start']) || !is_numeric($_GET['start']) || (int)$_GET['start'] < 1) { 
			$_GET['start'] = 0; 
		}
		$start = $_GET['start'];
		
		if($products = DataObject::get('ClickBankProduct', "`ParentID` = '{$this->ID}'", 'Created DESC', '', "{$start}, {$this->ItemsPerPage}")) {
			return $products;
		}

		return false;
	}
}