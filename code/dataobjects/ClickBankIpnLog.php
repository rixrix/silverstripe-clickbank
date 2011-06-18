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
				new TextField('cproditem', _t('ClickBank.IL_CPRODITEM')),
				new TextField('cprodtitle', _t('ClickBank.IL_CPRODTITLE')),
				new TextField('cprodtype', _t('ClickBank.IL_CPRODTYPE')),
				new TextField('ctransaffiliate', _t('ClickBank.IL_CTRANSAFFILIATE')),
				new TextField('caccountamount', _t('ClickBank.IL_CACCOUNTMOUNT')),
				new TextField('corderamount', _t('ClickBank.IL_CORDERAMOUNT')),
				new TextField('ctranspaymentmethod', _t('ClickBank.IL_CTRANSPAYMENTMETHOD')),
				new TextField('ccurrency', _t('ClickBank.IL_CCURRENCY')),
				new TextField('ctranspublisher', _t('ClickBank.IL_CTRANSPUBLISHER')),
				new TextField('ctransrole', _t('ClickBank.IL_CTRANSROLE')),
				new TextField('ctransreceipt', _t('ClickBank.IL_CRECEIPT')),
				new TextField('ctid', _t('ClickBank.IL_CTID')),
				new TextField('ctranstime', _t('ClickBank.IL_CTRANSTIME')),
				new TextField('cvendthru', _t('ClickBank.IL_CVENDTHRU')),
				new TextField('cupsellreceipt', _t('ClickBank.IL_CUPSELLRECEIPT')),
				new TextField('ctransaction', _t('ClickBank.IL_CTRANSACTION')),
				new TextField('crebillamnt', _t('ClickBank.IL_CREBILLAMNT')),
				new TextField('cprocessedpayments', _t('ClickBank.IL_CPROCESSEDPAYMENTS')),
				new TextField('cfuturepayments', _t('ClickBank.IL_CFUTUREPAYMENTS')),
				new TextField('cnextpaymentdate', _t('ClickBank.IL_CNEXTPAYMENTDATE')),
				new TextField('crebillstatus', _t('ClickBank.IL_CREBILLSTATUS')),
			)
		);
		
		return $fields;
	}
	
	public function ctranstime() {
		return date('Y/m/d', $this->dbObject('ctranstime')->getValue());
	}
}