<?php
/**
 * An extension to memberprofiles module. This will a new tab in memberprofile content
 * tab 
 *   
 * @package		silverstripe-clickbank
 * @subpackage	extensions
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class MemberProfilePageExtension extends DataObjectDecorator {
	public function extraStatics() {
		return array (
			'db' => array (
				'ClickBankPageTitle'   => 'Varchar(255)',
				'ClickBankPageContent' => 'HTMLText',
			)
		);
	}
	
	/**
	 * Displays new ClickBank tab in MemberProfile CMS field
	 * 
	 * @return	Object	ClickBank Tab
	 */
	public function updateCMSFields($fields) {
		$fields->addFieldToTab('Root.Content.ClickBank', new TextField('ClickBankPageTitle', 'Title'));
		$fields->addFieldToTab('Root.Content.ClickBank', new HtmlEditorField('ClickBankPageContent', 'Content'));
		$fields->addFieldToTab('Root.Content.ClickBank', new LabelField('ReplacementTextHeader', 'Replacements: $CBReceipt = Last ClickBank transaction receipt'));
		
		return $fields;
	}
}

/**
 * MemberProfilePage_Controller extension
 */
class MemberProfilePageExtension_Controller extends Extension {
	public static $allowed_actions = array (
		'ClickBankContent',
		'download'
	);

	/**
	 * Displays clickbank page in memberprofiles tab
	 * 
	 * @param	none
	 * @return	object	arraydata
	 */
	public function ClickBankContent() {
		$member = Member::currentUser();
		if ($member && (Permission::check('ADMIN') || $member->ClickBankAccountType != 'None')) {
			$name = "{$member->FirstName} {$member->Surname}";
			$title = $this->owner->ClickBankPageTitle;
			$content = $this->owner->ClickBankPageContent;
			
			$memberLastTransactionReceipt = $member->ClickBankProfile()->LastTransactionReceipt;
			$content = str_replace('$CBReceipt', $memberLastTransactionReceipt, $content);
			
			return new ArrayData (
				array (
					'Title' => $title,
					'Content' => $content,
					'Name' => $name
				)
			);
		}
		return false;
	}
	
	/**
	 * Sends download request to registered members
	 * 	
	 * @param	object	GET 'filename' request 
	 * @return	object	HTTP request
	 */
	public function download(SS_HTTPRequest $request) {
		$name = $request->getVar('filename');
		if (Member::currentUserID() && $request->isGET() && !empty($name)) {
			$filename = DB::query("SELECT Filename FROM File  WHERE Name = '" . Convert::raw2sql($name) . "'")->value();
			if (!empty($filename) && Director::fileExists($filename)) {
				$file_contents = file_get_contents(Director::getAbsFile($filename));
				return SS_HTTPRequest::send_file($file_contents, $name);
			}
		}
		return ErrorPage::response_for(404);
	}
}