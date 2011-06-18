<?php
/**
 * Main ClickBank module handler
 * 
 * @package		silverstripe-clickbank
 * @subpackage	code
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBank {
	
	/**
	 * Enable ClickBank module
	 */
	public static function enable() {
		DataObject::add_extension('SiteConfig', 'ClickBankSiteConfig');
		if (class_exists('MemberProfileExtension')) {		
			DataObject::add_extension('MemberProfilePage', 'MemberProfilePageExtension');
			DataObject::add_extension('MemberProfilePage_Controller', 'MemberProfilePageExtension_Controller');
			DataObject::add_extension('Member', 'ClickBankMemberProfileExtension');
		}
	}
	
	/**
	 * Check that the required modules are installed.
	 * 
	 * @param	none
	 * @return	boolean		true/false
	 */
	public static function validate_required_modules() {
		if (class_exists('MemberProfileExtension')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Validates IPN response
	 * 
	 * @see		ClickBank IPN docs
	 * @param 	array 	$cbPostFields
	 * @return	boolean true/false
	 */
	public static function validate_ipn_request($cbPostFields) {
		$siteConfig = SiteConfig::current_site_config();
		$secretkey = $siteConfig->ClickBankSecretKey;
		
		$pop = "";
		$ipnFields = array();
		foreach($cbPostFields as $key => $val) {
			if ($key == "cverify") continue;
			$ipnFields[] = $key;
		}
		
		sort($ipnFields);
		foreach ($ipnFields as $field) {
			$pop .= $cbPostFields[$field] . "|";
		}
		
		$pop .= $secretkey;
		$calcedVerify = sha1(mb_convert_encoding($pop, "UTF-8"));
    	$calcedVerify = strtoupper(substr($calcedVerify,0,8));

    	return $calcedVerify == $cbPostFields["cverify"];
	}
	
	/**
	 * Validates after purchase request. This is usually the Thank You page
	 * 
	 * Query string $GET
	 * ---
	 * 	ClickBank receipt number (cbreceipt)
	 * 	Epoch time of the order (time & seconds)
	 * 	ClickBank item number (item)
	 * 	ClickBank proof of purchase (cbpop)
	 * 	Customer name (cname)
	 * 	Customer e-mail (cemail)
	 * 	Customer zip (czip)
	 * 	Customer country (ccountry)
	 * 	Affiliate nickname (cbaffi)
	 * 	Vendor variables (up to 128 bytes)
	 * 
	 * @param array		$_POST $cbPostFields
	 * @return	boolean	true/false
	 */
	public static function validate_afterpurchase_request($cbPostFields) {
		if (!empty($cbPostFields) && !empty($cbPostFields['cbreceipt']) && 
			!empty($cbPostFields['time']) && !empty($cbPostFields['item']) && 
			!empty($cbPostFields['cbpop'])) {
			
			$siteConfig = SiteConfig::current_site_config();
			$secretkey = $siteConfig->ClickBankSecretKey;
		
			$rcpt = $cbPostFields['cbreceipt'];
	  		$time = $cbPostFields['time'];
	  		$item = $cbPostFields['item'];
	  		$cbpop = $cbPostFields['cbpop'];
	 
	  		$xxpop=sha1("$secretkey|$rcpt|$time|$item");
	  		$xxpop=strtoupper(substr($xxpop,0,8));
	  		
	  		return $cbpop == $xxpop;
		}
  		return false;
	}
	
	/**
	 * Process IPN request
	 * 	- add new member
	 * 	- populate IPN log
	 * 
	 * @todo	search for existing member
	 * 
	 * @param 	SS_HTTPRequest $request
	 * @return	boolean
	 */
	public static function process_ipn_request($data) {
		if (is_array($data)) {
			if (!empty($data['ccustemail'])) {
				$member = DataObject::get_one("Member", "Email = '{$data['ccustemail']}'");
				if ($member) {
					return self::updateMember($member, $data);
				} else {
					return self::addMember($data);	
				}
			} 
		}
		return false; 
	}
	
	/**
	 * Add new member
	 * 
	 * @param	array	member data. 
	 * @return	boolean	true/false
	 */
	public function addMember($data) {
		if (is_array($data)) {
			$member = new Member();
			$member->Email = $data['ccustemail'];
			$member->FirstName = ucwords(strtolower($data['ccustfirstname']));					
			$member->Surname = ucwords(strtolower($data['ccustlastname']));
			$member->ClickBankAccountType = 'Paid';
			
			/* CORE BUG: password doesn't work at all */
			$password = Member::create_new_password();
			$security = Security::encrypt_password($password);
			$member->Password = $security['password'];
			$member->Salt = $security['salt'];
			
			/* link to memberprofilepage module */
			$profilePage = DataObject::get_one('MemberProfilePage');
			$member->ProfilePageID = $profilePage->ID;
			if ($profilePage->EmailType == 'Validation') {
				$member->NeedsValidation = true;	
			}
			
			/* populate new member profile */
			$clickBankProfile = new ClickBankMemberProfile();
			if ($clickBankProfile) {
				foreach($data as $key => $val) {
					if (isset($clickBankProfile->{$key})) {
						if ($clickBankProfile->{$key} != $val) {
							$clickBankProfile->{$key} = $val;
						}
					}
					
					if ($key == 'ctransreceipt') {
						$clickBankProfile->LastTransactionReceipt = $val;
					}
					
					if ($key == 'ctranstime') {
						$clickBankProfile->LastTransactionTime = $val;
					}
					
					if ($key == 'ctid') {
						$clickBankProfile->LastTransactionID = $val;
					}
					
					if ($key == 'ctransaction') {
						$clickBankProfile->LastTransactionType = $val;
					}
				}
			}
			$clickBankProfile->write();
			
			$member->ClickBankProfileID = $clickBankProfile->ID; 
			$member->write();
			
			/* Populate log */
			$clickBankIpnLog = new ClickBankIpnLog();
			$clickBankIpnLog->MemberID = $member->ID;
			foreach($data as $key => $val) {
				if (isset($clickBankIpnLog->{$key})) {
					$clickBankIpnLog->{$key} = $val;
				}
			}
			$clickBankIpnLog->write();
			
			$email = new MemberConfirmationEmail($member->ProfilePage(), $member);
			$email->send();
			
			return true;
		}
		return false;
	}
	
	/**
	 * Updates member's info, profile and transaction log
	 * 
	 * @param	object	$member
	 * @param	array	$data
	 * @return	boolean	true/false
	 */
	public function updateMember($member, $data) {
		if ($member && is_array($data)) {
			$member->FirstName = ucwords(strtolower($data['ccustfirstname']));
			$member->Surname = ucwords(strtolower($data['ccustlastname']));
						
			/* Update member data */
			$clickBankProfile = DataObject::get_one('ClickBankMemberProfile', "ID = '{$member->ClickBankProfileID}'");
			if ($clickBankProfile) {
				foreach($data as $key => $val) {
					if (isset($clickBankProfile->{$key})) {
						if ($clickBankProfile->{$key} != $val) {
							$clickBankProfile->{$key} = $val;
						}
					}
					
					if ($key == 'ctransreceipt') {
						$clickBankProfile->LastTransactionReceipt = $val;
					}
					
					if ($key == 'ctranstime') {
						$clickBankProfile->LastTransactionTime = $val;
					}
					
					if ($key == 'ctid') {
						$clickBankProfile->LastTransactionID = $val;
					}
					
					if ($key == 'ctransaction') {
						$clickBankProfile->LastTransactionType = $val;
					}
				}
			}
			
			/* Populate log */
			$clickBankIpnLog = new ClickBankIpnLog();
			$clickBankIpnLog->MemberID = $member->ID;
			foreach($data as $key => $val) {
				if (isset($clickBankIpnLog->{$key})) {
					$clickBankIpnLog->{$key} = $val;
				}
			}
			
			$member->write();
			$clickBankProfile->write();
			$clickBankIpnLog->write();
			
			return true;
		}
		return false;
	}
}