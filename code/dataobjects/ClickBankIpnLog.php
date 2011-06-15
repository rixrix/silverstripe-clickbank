<?php
/**
 * A data object of all IPN logs/transaction for each member
 * 
 * @package		silverstripe-clickbank
 * @subpackage	dataobjects
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBankIpnLog extends DataObject {
	static $db = array (
		'cproditem' => 'Varchar(5)',
		'cprodtitle' => 'Varchar(255)',
		'cprodtype' => 'Varchar(11)',
		'ctransaffiliate' => 'Varchar(10)',
		'caccountamount' => 'Varchar(10)',
		'corderamount' => 'Varchar(10)',
		'ctranspaymentmethod' => 'Varchar(4)',
		'ccurrency' => 'Varchar(3)',
		'ctranspublisher' => 'Varchar(10)',
		'ctransrole' => 'Varchar(9)',
		'ctransreceipt' => 'Varchar(20)',
		'ctid' => 'Varchar(255)',
		'ctranstime' => 'Varchar(10)',
		'cvendthru' => 'Text',
	
		// ¤ Only populated when purchase is an upsell
		'cupsellreceipt' => 'Varchar(13)',
	
		// * See ÒTransaction TypesÓ
		'ctransaction' => 'Varchar(15)',
		
		// *** Only populated for recurring billing transactions
		'crebillamnt' => 'Varchar(10)',
		'cprocessedpayments' => 'Varchar(2)',
		'cfuturepayments' => 'Varchar(2)',
		'cnextpaymentdate' => 'Varchar(10)',
		'crebillstatus' => 'Varchar(10)'		
	);
	
	static $has_one = array (
		'Member' => 'Member'
	);
	
	static $summary_fields = array (
		'ID',
		'cprodtitle',
		'corderamount',
		'ccurrency',
		'ctransreceipt',
		'ctranstime' => 'ctranstime'
	);
	
	static $field_labels = array (
		'cprodtitle' => 'Prod. Name',
		'corderamount' => 'Order Amount',
		'ccurrency' => 'Currency',
		'ctransreceipt' => 'Receipt',
		'ctranstime' => 'Date'	
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab('Root.Main',
			array (
				new TextField('cproditem', 'Item #'),
				new TextField('cprodtitle', 'Product Name'),
				new TextField('cprodtype', 'Type'),
				new TextField('ctransaffiliate', 'Affiliate'),
				new TextField('caccountamount', 'Account Amount'),
				new TextField('corderamount', 'Order Amount'),
				new TextField('ctranspaymentmethod', 'Payment Method'),
				new TextField('ccurrency', 'Currency'),
				new TextField('ctranspublisher', 'Transaction Publisher'),
				new TextField('ctransrole', 'Transaction Role'),
				new TextField('ctransreceipt', 'Receipt'),
				new TextField('ctid', 'Transaction ID'),
				new TextField('ctranstime', 'Date of Transacton'),
				new TextField('cvendthru', 'cvendthru'),
				new TextField('cupsellreceipt', 'Upsell Receipt'),
				new TextField('ctransaction', 'Transaction Type'),
				new TextField('crebillamnt', 'Rebill Amount'),
				new TextField('cprocessedpayments', 'Processed Payments'),
				new TextField('cfuturepayments', 'Future Payments'),
				new TextField('cnextpaymentdate', 'Next Payment Date'),
				new TextField('crebillstatus', 'Rebill Status'),
			)
		);
		
		return $fields;
	}
	
	public function ctranstime() {
		return date('Y/m/d', $this->dbObject('ctranstime')->getValue());
	}
}