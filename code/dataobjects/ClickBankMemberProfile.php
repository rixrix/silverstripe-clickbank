<?php
/**
 * Member's extended ClickBank profile
 * 
 * @package		silverstripe-clickbank
 * @subpackage	dataobjects
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankMemberProfile extends DataObject {
	static $db = array (
		'ccuststate' => 'Varchar(2)',
		'ccustzip' => 'Varchar(16)',
		'ccustcc' => 'Varchar(2)',
		
		// ** Only populated for vendor notifications
		'ccustaddr1' => 'Varchar(255)',
		'ccustaddr2' => 'Varchar(255)',
		'ccustcity' => 'Varchar(255)',
		'ccustcounty' => 'Varchar(255)',
		'ccustshippingstate' => 'Varchar(2)',
		'ccustshippingzip' => 'Varchar(255)',
		'ccustshippingcountry' => 'Varchar(255)',
	
		'LastTransactionReceipt' => 'Varchar(20)',
		'LastTransactionTime' => 'Varchar(10)',
		'LastTransactionID' => 'Varchar(255)',
		'LastTransactionType' => 'Varchar(10)'
	);
	
}

