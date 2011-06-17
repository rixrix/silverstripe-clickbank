<?php
/**
 * ClickBank module controller
 * 
 * @package		silverstripe-clickbank
 * @subpackage	controllers
 * @author 		Richard Sentino <rix@mindginative.com>
 */
class ClickBank_Controller extends Controller {
	
	static $url_handlers = array (
		'' => 'index',
		'ipn!' => 'ipn',
		'download/$Filename!' => 'download'
	);
	
	function index(SS_HTTPRequest $request) {
		return Director::redirect('/server-error');
	}
	
	/**
	 * Process IPN request from ClickBank. Only process POST request
	 * 
	 * @param	object	$_POST
	 * @return	int		HTTP code 
	 */
	public function ipn(SS_HTTPRequest $request) {
		if ($request->isPost()) {
			if (ClickBank::validate_ipn_request($request->postVars())) {
				ClickBank::process_ipn_request($request->postVars());
				return Director::get_status_code();
			}
		}
		return ErrorPage::response_for(404); 		
	}
	
	/**
	 * Sends download request to registered members
	 * 	
	 * @param	object	GET 'filename' request 
	 * @return	object	HTTP request
	 */
	public function download(SS_HTTPRequest $request) {
		$filename = $request->param('Filename');
		if (Member::currentUserID() && $request->isGET() && !empty($filename)) {
			$file = DB::query("SELECT Filename FROM File  WHERE Name = '" . Convert::raw2sql($filename) . "'")->value();
			if (!empty($file) && Director::fileExists($file)) {
				$file_contents = file_get_contents(Director::getAbsFile($file));
				return SS_HTTPRequest::send_file($file_contents, $filename);
			}
		}
		return Security::permissionFailure($this);
	}	
}