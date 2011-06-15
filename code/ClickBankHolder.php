<?php
/**
 * ClickBank product page holder 
 * 
 * @package		silverstripe-clickbank
 * @subpackage	code
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankHolder extends Page {
	static $allowed_children = array (
		'ClickBankProduct'
	);
}

class ClickBankHolder_Controller extends Page_Controller {
}